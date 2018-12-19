<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\IdentityUser;
use App\Entity\LivraisonUser;
use App\Entity\ModeLivraison;
use App\Entity\ModePayment;
use App\Form\IdentityUserType;
use App\Form\LivraisonUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommanderController extends AbstractController
{
    /**
    * @Route("/commander", name="commander")
    */
    public function index(UserInterface $user, Request $request, ObjectManager $manager)
    {
        // verifier que le panier n'est pas vide
        $articlesPanier = $user->getPanier()->getArticles();
        if($articlesPanier->isEmpty()){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si votre panier est vide.');
            return $this->redirectToRoute('boutique');
        }

        // voir si l' entité existe
        $civil = $user->getIdentityUser();
        if($civil == null){
            // si il est inexistant, créer le form
            $civil = new IdentityUser();
        }
        
        $form = $this->createForm(IdentityUserType::class, $civil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $civil->setUser($user);
            
            $manager->persist($civil);
            $manager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistré !');
            return $this->redirectToRoute('livraison');
        }

        return $this->render('commander/information.html.twig', [
            'controller_name' => 'Commander',
            'form' => $form->createView(),
            'title' => 'Commander'
        ]); 
    }

    /**
     * @Route("/livraison/{choice}", name="livraison")
     */
    public function livraison($choice = null, UserInterface $user, Request $request, ObjectManager $manager)
    {
        // verifier que les informations pour acceder a cette page sont bien remplis
        $articlesPanier = $user->getPanier()->getArticles();
        $civil = $user->getIdentityUser();
        if($articlesPanier->isEmpty()){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si votre panier est vide.');
            return $this->redirectToRoute('boutique');
        }else if($civil == null){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations personnelles ne sont pas remplies.');
            return $this->redirectToRoute('commander');
        }

        // voir si le entité existe
        $livraison = $user->getLivraisonUser();
        //new Session pour recup les sessions
        $session = new Session();
        // récup une session définis
        $sessionModeLivraison = $session->get('modifier');

        if($livraison == null OR $sessionModeLivraison == 1){
            if($livraison == null){
                $livraison = new LivraisonUser();
            }

            $form = $this->createForm(LivraisonUserType::class, $livraison);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $livraison->setUser($user);
                $manager->persist($livraison);
                $manager->flush();
                // apres que les modifications on était faite, remmettre a zéro la demande de modification
                $session->set('modifier', 0);

                $this->addFlash('success', 'Vos informations ont bien été enregistré !');
                return $this->redirectToRoute('livraison');
            }
            
            return $this->render('commander/livraison.html.twig', [
                'controller_name' => 'Livraison',
                'form' => $form->createView(),
                'title' => 'Commander'
            ]);
        }

        
        // different mode de livraison
        $repo=$this->getDoctrine()->getRepository(ModeLivraison::class);
        $modeLivraison = $repo->findAll(array(), array('id' => 'asc'));


        if($choice != null){
            // si choice = 0 (comme ce n'est pas un id posible,on attribut cette valeur au btn modifier)
            // faire une session permettant de retourner au form de modification
            if($choice == 0){
                $session->set('modifier', 1);
                return $this->redirectToRoute('livraison');

            // session pour ajouter le mode de livraison
            }else{
                $session->set('modeLivraison', $choice);
                return $this->redirectToRoute('payement'); 
            }
        }

        return $this->render('commander/modelivraison.html.twig', [
            'controller_name' => 'Livraison',
            'title' => 'Commander',
            'infoLivraison' => $livraison,
            'modeLivraison' => $modeLivraison,
        ]);
        
    }

    /**
    * @Route("/payement/{choice}", name="payement")
    */
    public function payement($choice = null, UserInterface $user)
    {

    $session = new Session();
    // récup une session définis
    $sessionModeLivraison = $session->get('modeLivraison');
    
    // verification que les valeurs des sessions existe
    $modeLivraison = $this->getDoctrine()
                          ->getRepository(ModeLivraison::class)
                          ->createQueryBuilder('c')
                          ->where('c.id = :id')
                          ->setParameter('id', $sessionModeLivraison)
                          ->setMaxResults(1)
                          ->getQuery()
                          ->getSingleResult();

    // verifier que les informations pour acceder a cette page sont bien remplis
    $articlesPanier = $user->getPanier()->getArticles();
    $civil = $user->getIdentityUser();
    $livraison = $user->getLivraisonUser();
    if($articlesPanier->isEmpty()){
        $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si votre panier est vide.');
        return $this->redirectToRoute('boutique');
    }else if($civil == null){
        $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations personnelles ne sont pas remplies.');
        return $this->redirectToRoute('commander');
    }else if($livraison == null){
        $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations de livraisons ne sont pas remplies.');
        return $this->redirectToRoute('livraison');
    }else if($modeLivraison == null){
        $this->addFlash('warning', 'Vous devez choisir un mode de livraison.');
        return $this->redirectToRoute('livraison');
    }

     // different mode de livraison
     $repo=$this->getDoctrine()->getRepository(ModePayment::class);
     $modePayment = $repo->findAll(array(), array('id' => 'asc'));

     if($choice != null){
        $session->set('modePayment', $choice);
        return $this->redirectToRoute('valider'); 
     }
   
        return $this->render('commander/payement.html.twig', [
            'controller_name' => 'Payement',
            'title' => 'Payement',
            'modePayment' => $modePayment,
        ]);
    }

    /**
     * @Route("/valider", name="valider")
     */
    public function valider(UserInterface $user, ObjectManager $manager)
    {
        $session = new Session();
        // récup une session définis
        $sessionModeLivraison = $session->get('modeLivraison');
        $sessionModePayment = $session->get('modePayment');
        // verification que les valeurs des sessions existe
        $modeLivraison = $this->getDoctrine()
                              ->getRepository(ModeLivraison::class)
                              ->createQueryBuilder('c')
                              ->where('c.id = :id')
                              ->setParameter('id', $sessionModeLivraison)
                              ->setMaxResults(1)
                              ->getQuery()
                              ->getSingleResult();

        $modePayment = $this->getDoctrine()
                             ->getRepository(ModePayment::class)
                             ->createQueryBuilder('c')
                             ->where('c.id = :id')
                             ->setParameter('id', $sessionModePayment)
                             ->setMaxResults(1)
                             ->getQuery()
                             ->getSingleResult();
    
        // verifier que les informations pour acceder a cette page sont bien remplis
        $articlesPanier = $user->getPanier()->getArticles();
        $civil = $user->getIdentityUser();
        $livraison = $user->getLivraisonUser();
        if($articlesPanier->isEmpty()){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si votre panier est vide.');
            return $this->redirectToRoute('boutique');
        }else if($civil == null){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations personnelles ne sont pas remplies.');
            return $this->redirectToRoute('commander');
        }else if($livraison == null){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations de livraisons ne sont pas remplies.');
            return $this->redirectToRoute('livraison');
        }else if($modeLivraison == null){
            $this->addFlash('warning', 'Vous devez choisir un mode de livraison.');
            return $this->redirectToRoute('livraison');
        }else if($modePayment == null){
            $this->addFlash('warning', 'Vous devez choisir un mode de Payement.');
            return $this->redirectToRoute('payement');
        }

        // afficher les informations du client
        $rawSql =   "SELECT titre, description, image, prix FROM produit AS p WHERE p.panier_id = (SELECT id FROM fos_user AS i WHERE i.id = :iduser)";
        $stmt = $manager->getConnection()->prepare($rawSql);
        $stmt->bindValue('iduser', $user->getId());
        $stmt->execute();
        $articlesPanier = $stmt->fetchAll();

        return $this->render('commander/valider.html.twig', [
            'controller_name' => 'Valider',
            'title' => 'Valider',
            'articlesPanier' => $articlesPanier,
            'infoLivraison' => $livraison,
            'infoCivil' => $civil,
            'modePayement' => $modePayment,
            'modeLivraison' => $modeLivraison,
        ]);
    }
}
