<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\IdentityUser;
use App\Entity\LivraisonUser;
use App\Entity\ModeLivraison;
use App\Form\IdentityUserType;
use App\Form\LivraisonUserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
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

        // si le entité existe afficher les info
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
     * @Route("/livraison", name="livraison")
     */
    public function livraison(UserInterface $user, Request $request, ObjectManager $manager)
    {
        // verifier que le panier n'est pas vide et que les informations personnelles sont remplis
        $articlesPanier = $user->getPanier()->getArticles();
        $civil = $user->getIdentityUser();
        if($articlesPanier->isEmpty()){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si votre panier est vide.');
            return $this->redirectToRoute('boutique');
        }else if($civil == null){
            $this->addFlash('warning', 'Vous ne pouvez pas passer de commande si vos informations personnelles ne sont pas remplies.');
            return $this->redirectToRoute('commander');
        }

        // si le entité existe afficher les info
        $livraison = $user->getLivraisonUser();

        if($livraison == null){ //OR $session['patate'] == 1){
           // $session['patate'] = 0
             // si il est inexistant, créer le form
             $livraison = new LivraisonUser();

             $form = $this->createForm(LivraisonUserType::class, $livraison);
             $form->handleRequest($request);
             if ($form->isSubmitted() && $form->isValid()) {
                 
                $livraison->setUser($user);
                $manager->persist($livraison);
                $manager->flush();
     
                 $this->addFlash('success', 'Vos informations ont bien été enregistré !');
                 return $this->redirectToRoute('livraison');
             }
            
            return $this->render('commander/livraison.html.twig', [
                'controller_name' => 'Livraison',
                'form' => $form->createView(),
                'title' => 'Commander'
            ]);
        }

        $thisLivraison = $this->getDoctrine()
                         ->getRepository(LivraisonUser::class)
                         ->createQueryBuilder('c')
                         ->where('c.user = :user')
                         ->setParameter('user', $user)
                         ->setMaxResults(1)
                         ->getQuery()
                         ->getSingleResult();

        $repo=$this->getDoctrine()->getRepository(ModeLivraison::class);
        $modeLivraison = $repo->findAll();


        return $this->render('commander/modelivraison.html.twig', [
            'controller_name' => 'Livraison',
            'title' => 'Commander',
            'infoLivraison' => $thisLivraison,
            'modeLivraison' => $modeLivraison,
        ]);
        
    }

     /**
     * @Route("/payement", name="payement")
     */
    public function payement()
    {
        return $this->render('commander/payement.html.twig', [
            'controller_name' => 'Payement',
            'title' => 'Payement'
        ]);
    }

    /**
     * @Route("/valider", name="valider")
     */
    public function valider()
    {
        return $this->render('commander/valider.html.twig', [
            'controller_name' => 'Valider',
            'title' => 'Valider'
        ]);
    }
}
