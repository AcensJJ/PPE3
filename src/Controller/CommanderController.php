<?php

namespace App\Controller;

use App\Entity\IdentityOrder;
use App\Form\IdentityOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommanderController extends AbstractController
{
    /**
     * @Route("/commander", name="commander")
     */
    public function index(Request $request)
    {
        $civil = new IdentityOrder();

        $form = $this->createForm(IdentityOrderType::class, $civil);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Vos informations ont bien été enregistré !');
            return $this->redirectToRoute('payement');
        }

        return $this->render('commander/information.html.twig', [
            'controller_name' => 'Commander',
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
