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

        // ajouter les valeurs aux entités
        $civil = new IdentityOrder();
        $livraison = new LivraisonOrder();
        // les deux form , civil / livraison
        $form = $this->createForm(IdentityOrderType::class, $civil);
        $form->handleRequest($request);
        $form2 = $this->createForm(LivraisonOrderType::class, $livraison);
        $form2->handleRequest($request);
        if ($form2->isSubmitted() && $form->isValid() && $form2->isValid()) {

            $this->addFlash('success', 'Vos informations ont bien été enregistré !');
            return $this->redirectToRoute('payement');
        }

        $thisPanier = $thisPanier->getArticles();
        $countPanier = $thisPanier->count();

        if($countPanier != 0){
            return $this->render('commander/information.html.twig', [
                'controller_name' => 'Commander',
                'form' => $form->createView(),
                'form2' => $form2->createView(),
                'title' => 'Commander'
            ]);
        } else {
            return $this->redirectToRoute('panier');
        }
    }

    /**
     * @Route("/payement", name="payement")
     */
    public function payement()
    {
        return $this->render('commander/payement.html.twig', [
            'controller_name' => 'Payement',
        ]);
    }

    /**
     * @Route("/valider", name="valider")
     */
    public function valider()
    {
        return $this->render('commander/valider.html.twig', [
            'controller_name' => 'Valider',
        ]);
    }
}
