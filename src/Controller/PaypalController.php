<?php

namespace App\Controller;

use App\Entity\PaymentOrder;
use App\Entity\CommandeOrder;
use App\Entity\IdentityOrder;
use App\Entity\ModeLivraison;
use App\Entity\LivraisonOrder;
use App\Entity\FacturationOrder;
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
        $payer->setClientID("AW2Ur-EOSNaMkf0sBU39tZiMjcK-FIWYmiZHhXzVNF2sYrQkc6hgw7VE-jdmbVQSadkX8NsaYPWbQ8Td");
        $payer->setSecret("EPkj7CqKdISgHN7Xf84KFT0hXTBJYJN55gR8PAJs4ve1KBKmhi3w_fzZdRxCJdWK3UxjAdzwSdZXzfx_");
        //Contenu de la transaction pour paypal
        $totalPrix = 0;
        $myArticles = $user->getPanier();
        foreach ($myArticles as $article) {
            $totalPrix = $totalPrix + $article->getArticles()->getPrix();
        }
        // Prix de livraison
        $totalPrix = $totalPrix + $modeLivraison->getPrix();
        $totalPrix = strval($totalPrix); //INT TO STR
        $payment_data = [
            "intent" => "sale",
            "redirect_urls" => [
                "return_url" => "http://localhost:8000/orderSummary?r=return",
                "cancel_url" => "http://localhost:8000/orderSummary?r=cancel"
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
                    "description" => "Achat sur le site commercial Soldware"
                ]
            ]
        ];
        foreach ($myArticles as $key => $article) {
            $thisPrix = strval($article->getArticle()->getPrice()); //INT TO STR;
            $item = [
                "sku" => "1PK5Z9",
                "name" => $article->getArticle()->getTitle(),
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
        $msg = "Une erreur est survenue durant la communication avec les serveurs de PayPal. Merci de bien vouloir réessayer ultérieurement.";
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
            $payer->setClientID("AW2Ur-EOSNaMkf0sBU39tZiMjcK-FIWYmiZHhXzVNF2sYrQkc6hgw7VE-jdmbVQSadkX8NsaYPWbQ8Td");
            $payer->setSecret("EPkj7CqKdISgHN7Xf84KFT0hXTBJYJN55gR8PAJs4ve1KBKmhi3w_fzZdRxCJdWK3UxjAdzwSdZXzfx_");
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
                    $identityOrder->setCivility($identityUser->getCivility())
                        ->setPrenom($identityUser->getPrenom())
                        ->setNom($identityUser->getNom())
                        ->setEmail($identityUser->getEmail())
                        ->setTelephone($identityUser->getTelephone())
                        ->setNameSociety($identityUser->getNameSociety());
                    $manager->persist($identityOrder);
                    //Création FacturationOrder
                    $facturationOrder = new FacturationOrder();
                    $facturationUser = $user->getFacturationUser();
                    $facturationOrder->setAdresse($facturationUser->getAdresse())
                        ->setPays($facturationUser->getPays())
                        ->setComplementAdresse($facturationUser->getComplementAdresse())
                        ->setEtatRegion($facturationUser->getEtatRegion())
                        ->setVille($facturationUser->getVille())
                        ->setCodePostal($facturationUser->getCodePostal());
                    $manager->persist($facturationOrder);
                    //Création LivraisonOrder
                    $livraisonOrder = new LivraisonOrder();
                    $livraisonUser = $user->getLivraisonUser();
                    $livraisonOrder->setAdresse($livraisonUser->getAdresse())
                        ->setPays($livraisonUser->getPays())
                        ->setComplementAdresse($livraisonUser->getComplementAdresse())
                        ->setEtatRegion($livraisonUser->getEtatRegion())
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
                        ->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $dateNow))
                        ->setUser($user)
                        ->setLivraisonOrder($livraisonOrder)
                        ->setFacturationOrder($facturationOrder)
                        ->setIdentityOrder($identityOrder)
                        ->setPaymentOrder($paymentOrder)
                        ->setModeLivraison($modeLivraison);
                    $articlesCart = $user->getCart();
                    foreach ($articlesCart as $articleCart) {
                        $commande->addLignesArticle($articleCart);
                        $articleCart->setOrdered(1);
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
}