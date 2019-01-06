<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\PaymentOrder;
use App\Entity\CommandeOrder;
use App\Entity\IdentityOrder;
use App\Entity\ModeLivraison;
use App\Entity\LivraisonOrder;
use App\AJ\CommandeBundle\UniqRef;
use App\AJ\PayPalBundle\PayPalPayment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaypalController extends AbstractController
{
    /**
     * @Route("/paypal/create_payment", name="paypal_create")
     */
    public function paypal_create(UserInterface $user, Request $request, ObjectManager $manager)
    {
        $session = new Session();
        // récup la valeur de la livraison
        $sessionModeLivraison = $session->get('modeLivraison');
        $modeLivraison = $this->getDoctrine()
                              ->getRepository(ModeLivraison::class)
                              ->createQueryBuilder('c')
                              ->where('c.id = :id')
                              ->setParameter('id', $sessionModeLivraison)
                              ->setMaxResults(1)
                              ->getQuery()
                              ->getSingleResult();
        //déclarations variables
        $success = 0;
        $msg = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement... (ERROR PAYP01)";
        $paypal_response = [];

        //Initialisation payment paypal
        $payer = new PayPalPayment();
        $payer->setSandboxMode(1);
        $payer->setClientID("AVJ-jQhi2j33qieUgiUIWW7-Ajw5vXM347w5TdE6tdTgRNcj2prUVvKfRDO-nojCBl25TTfHuApaBnbE");
        $payer->setSecret("ELePGx4jxRIhM7AYHR_J2Ah3bFxLE0d6nnz_ez6iEKNxlrd1QgzGGEi4V7AcItS2_3gujHqaaqZ9NBSg");

        //Contenu de la transaction pour paypal
        $totalPrix = 0;
        $thisPanier = $user->getPanier();
        $myArticles = $thisPanier->getArticles();
        foreach ($myArticles as $article) {
            $totalPrix = $totalPrix + $article->getPrix();
        }

        // Prix de livraison
        $totalPrix = $totalPrix + $modeLivraison->getPrix();
        $totalPrix = strval($totalPrix); //INT TO STR
        $payment_data = [
            "intent" => "sale",
            "redirect_urls" => [
                "return_url" => "http://localhost:8000/commande/valider?r=return",
                "cancel_url" => "http://localhost:8000/commande/valider?r=cancel"
            ],
            "payer" => [
                "payment_method" => "paypal"
            ],
            "transactions" => [
                [
                    "amount" => [
                        "total" => $totalPrix,
                        "currency" => "EUR"
                    ],
                    "item_list" => [
                        "items" => []
                    ],
                    "description" => "Achat"
                ]
            ]
        ];

        foreach ($myArticles as $key => $article) {
            $thisPrix = strval($article->getPrix()); //INT TO STR;
            $item = [
                "sku" => "1PK5Z9",
                "name" => $article->getTitre(),
                "price" => $thisPrix,
                "currency" => "EUR"
            ];
            array_push($payment_data["transactions"][0]["item_list"]["items"], $item);
        }
        
        //Création du payment / envoi a paypal
        $paypal_response = json_decode($payer->createPayment($payment_data));
        //Si la création a été faites alors on entre les infos
        if (!empty($paypal_response->id)) {
            $paymentOrder = new PaymentOrder();
            $paymentOrder->setPaymentId($paypal_response->id);
            $paymentOrder->setStatus($paypal_response->state);
            $paymentOrder->setAmount($paypal_response->transactions[0]->amount->total);
            $paymentOrder->setCurrency($paypal_response->transactions[0]->amount->currency);
            $paymentOrder->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            $manager->persist($paymentOrder);
            $manager->flush();
            $success = 1;
            $msg = "";
        } else {
        $msg = "Une erreur est survenue durant la communication avec les serveurs de PayPal. Merci de bien vouloir réessayer ultérieurement. (ERROR PAYP03)";
        }
        return new JsonResponse(["success" => $success, "msg" => $msg, "paypal_response" => $paypal_response]);
    }

    /**
     * @Route("/paypal/execute_payment", name="paypal_execute")
     */
    public function paypal_execute(UserInterface $user, Request $request, ObjectManager $manager)
    {
        //Déclaration var
        $success = 0;
        $msg = "Une erreur est survenue, merci de bien vouloir réessayer ultérieurement... (ERROR PAYP02)";
        $paypal_response = [];
        //Récup arguments
        $paymentID = $request->request->get('paymentID');
        $payerID = $request->request->get('payerID');

        if (!empty($paymentID) AND !empty($payerID)) {
            $payer = new PayPalPayment();
            $payer->setSandboxMode(1);
            $payer->setClientID("AXFVKVzIJWsAegs8J8hHXJhA0YOCh8vQ6qXwFizOXCi99PFmCq_4ewwNi8nYHqzBmJpNA3NGpufMgpB6");
            $payer->setSecret("ELePGx4jxRIhM7AYHR_J2Ah3bFxLE0d6nnz_ez6iEKNxlrd1QgzGGEi4V7AcItS2_3gujHqaaqZ9NBSg");
            $repo = $this->getDoctrine()->getRepository(PaymentOrder::class);
            $paymentOrder = $repo->createQueryBuilder('c')
                                 ->where('c.paymentId = :paymentId')
                                 ->setParameter('paymentId', $paymentID)
                                 ->setMaxResults(1)
                                 ->getQuery()
                                 ->getSingleResult();

            if ($paymentOrder) {
                $paypal_response = json_decode($payer->executePayment($paymentID, $payerID));

                if ($paypal_response->state == "approved") {
                    //Mise a jour du paiement
                    $paymentOrder->setStatus($paypal_response->state);
                    $paymentOrder->setPayerPaypalEmail($paypal_response->payer->payer_info->email);
                    $manager->persist($paymentOrder);
                    $manager->flush();

                    //Création IdentityOrder
                    $identityOrder = new IdentityOrder();
                    $identityUser = $user->getIdentityUser();
                    $identityOrder->setPrenom($identityUser->getPrenom())
                                  ->setNom($identityUser->getNom())
                                  ->setEmail($identityUser->getEmail())
                                  ->setNumTel($identityUser->getNumTel());
                    $manager->persist($identityOrder);

                    //Création LivraisonOrder
                    $livraisonOrder = new LivraisonOrder();
                    $livraisonUser = $user->getLivraisonUser();
                    $livraisonOrder->setAdresse($livraisonUser->getAdresse())
                                   ->setPays($livraisonUser->getPays())
                                   ->setVille($livraisonUser->getVille())
                                   ->setCodePostal($livraisonUser->getCodePostal());
                    $manager->persist($livraisonOrder);

                    //Création de la commande
                    $ref = new UniqRef();
                    $reference = $ref->generateRef();
                    $dateNow = date('Y-m-d H:i:s');
                    $session = new Session();
                    $sessionModeLivraison = $session->get('modeLivraison');
                    $repolivraison = $this->getDoctrine()->getRepository(ModeLivraison::class);
                    $modeLivraison = $repolivraison->find($sessionModeLivraison);

                    $commande = new CommandeOrder();
                    $commande->setReference($reference)
                             ->setStatus('En attente')
                             ->setDate(\DateTime::createFromFormat('Y-m-d H:i:s', $dateNow))
                             ->setUser($user)
                             ->setLivraison($livraisonOrder)
                             ->setIdentity($identityOrder)
                             ->setPaymentOrder($paymentOrder)
                             ->setModeLivraison($modeLivraison);
                    
                    // recuperer le panier
                    $thisPanier = $user->getPanier();
                    $articlesPanier = $thisPanier->getArticles();
                    foreach ($articlesPanier as $article) {
                        // ajouter les articles du panier à la commande
                        $commande->addArticle($article);
                        // vider le panier
                        $thisPanier->removeArticle($article);
                        $manager->persist($thisPanier);
                    }
                    $manager->persist($commande);
                    $manager->flush();
                
                    //Delete Sessions
                    $session->set('modePayment', null);
                    $session->set('modeLivraison', null);
                    //Create Session def commande
                    $session->set('commandeOrder', $commande);
                    //Retourner success true
                    $success = 1;
                    $msg = "";
                } else {
                    $msg = "Une erreur est survenue durant l'approbation de votre paiement. Merci de réessayer ultérieurement ou contacter un administrateur du site.";
                }
            } else {
                $msg = "Votre paiement n'a pas été trouvé dans notre base de données. Merci de réessayer ultérieurement ou contacter un administrateur du site. (Votre compte PayPal n'a pas été débité)";
            }
        }
        return new JsonResponse(["success" => $success, "msg" => $msg, "paypal_response" => $paypal_response]);
    }

    /**
     * @Route("/paypal/valider", name="paypal_valider")
     */
    public function paypal_valider(UserInterface $user, Request $request, ObjectManager $manager)
    {

        $session = new Session();
        // récup la valeur de la livraison
        $sessionModeLivraison = $session->get('modeLivraison');
        $modeLivraison = $this->getDoctrine()
                              ->getRepository(ModeLivraison::class)
                              ->createQueryBuilder('c')
                              ->where('c.id = :id')
                              ->setParameter('id', $sessionModeLivraison)
                              ->setMaxResults(1)
                              ->getQuery()
                              ->getSingleResult();
                              
        //Contenu de la transaction pour paypal
        $totalPrix = 0;
        $thisPanier = $user->getPanier();
        $myArticles = $thisPanier->getArticles();
        foreach ($myArticles as $article) {
            $totalPrix = $totalPrix + $article->getPrix();
        }

        // Prix de livraison
        $totalPrix = $totalPrix + $modeLivraison->getPrix();
        $totalPrix = strval($totalPrix); //INT TO STR


        $paymentOrder = new PaymentOrder();
        $paymentOrder->setPaymentId("Pour le Paypal");
        $paymentOrder->setStatus("approved");
        $paymentOrder->setAmount($totalPrix);
        $paymentOrder->setCurrency("EUR");
        $paymentOrder->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
        $manager->persist($paymentOrder);
    
        //Création IdentityOrder
        $identityOrder = new IdentityOrder();
        $identityUser = $user->getIdentityUser();
        $identityOrder->setPrenom($identityUser->getPrenom())
                        ->setNom($identityUser->getNom())
                        ->setEmail($identityUser->getEmail())
                        ->setNumTel($identityUser->getNumTel());
        $manager->persist($identityOrder);

        //Création LivraisonOrder
        $livraisonOrder = new LivraisonOrder();
        $livraisonUser = $user->getLivraisonUser();
        $livraisonOrder->setAdresse($livraisonUser->getAdresse())
                        ->setPays($livraisonUser->getPays())
                        ->setVille($livraisonUser->getVille())
                        ->setCodePostal($livraisonUser->getCodePostal());
        $manager->persist($livraisonOrder);

        //Création de la commande
        $ref = new UniqRef();
        $reference = $ref->generateRef();
        $dateNow = date('Y-m-d H:i:s');
        $session = new Session();
        $sessionModeLivraison = $session->get('modeLivraison');
        $repolivraison = $this->getDoctrine()->getRepository(ModeLivraison::class);
        $modeLivraison = $repolivraison->find($sessionModeLivraison);

        $commande = new CommandeOrder();
        $commande->setReference($reference)
                    ->setStatus('En attente')
                    ->setDate(\DateTime::createFromFormat('Y-m-d H:i:s', $dateNow))
                    ->setUser($user)
                    ->setLivraison($livraisonOrder)
                    ->setIdentity($identityOrder)
                    ->setPaymentOrder($paymentOrder)
                    ->setModeLivraison($modeLivraison);

                    
        // recuperer le panier
        $thisPanier = $user->getPanier();
        $articlesPanier = $thisPanier->getArticles();
        foreach ($articlesPanier as $article) {
            // ajouter les articles du panier à la commande
            $commande->addArticle($article);
            // vider le panier
            $thisPanier->removeArticle($article);
            $manager->persist($thisPanier);
        }
        $manager->persist($commande);
        $manager->flush();

        //Delete Sessions
        $session->set('modePayment', null);
        $session->set('modeLivraison', null);
        //Create Session def commande
        $session->set('commandeOrder', $commande);
         
        return $this->redirectToRoute('terminer');
    }
}