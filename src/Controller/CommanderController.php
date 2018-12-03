<?php

namespace App\Controller;

use App\Entity\IdentityOrder;
use App\Entity\LivraisonOrder;
use App\Entity\Panier;
use App\Form\IdentityOrderType;
use App\Form\LivraisonOrderType;
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
        $thisPanier = $this->getDoctrine()
                         ->getRepository(Panier::class)
                         ->createQueryBuilder('c')
                         ->where('c.user = :user')
                         ->setParameter('user', $user)
                         ->setMaxResults(1)
                         ->getQuery()
                         ->getSingleResult();

        $civil = new IdentityOrder();
        
        $form = $this->createForm(IdentityOrderType::class, $civil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Vos informations ont bien été enregistré !');
            return $this->redirectToRoute('payement');
        }

        $thisPanier = $thisPanier->getArticles();
        $countPanier = $thisPanier->count();

        if($countPanier != 0){
            return $this->render('commander/information.html.twig', [
                'controller_name' => 'Commander',
                'form' => $form->createView(),
                'title' => 'Commander'
            ]);
        } else {
            return $this->redirectToRoute('panier');
        }
    }

    /**
     * @Route("/livraison", name="livraison")
     */
    public function livraison()
    {
        $livraison = new LivraisonOrder();
        $form = $this->createForm(LivraisonOrderType::class, $livraison);
        if ($form->isSubmitted() && $form->isValid()) {

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
