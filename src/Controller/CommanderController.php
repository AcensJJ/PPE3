<?php

namespace App\Controller;

use App\Entity\IdentityUser;
use App\Entity\LivraisonUser;
use App\Entity\Panier;
use App\Form\IdentityUserType;
use App\Form\LivraisonUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
        // si le entité existe afficher les info
        $livraison = null;

        if($livraison == null){
            // si il est inexistant, créer le form
            $livraison = new LivraisonUser();
        }
        
        $form = $this->createForm(LivraisonUserType::class, $livraison);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $livraison->setUser($user);

            $manager->persist($livraison);
            $manager->flush();

            $this->addFlash('success', 'Vos informations ont bien été enregistré !');
            return $this->redirectToRoute('payement');
        }

        return $this->render('commander/livraison.html.twig', [
            'controller_name' => 'Livraison',
            'form' => $form->createView(),
            'title' => 'Commander'
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
