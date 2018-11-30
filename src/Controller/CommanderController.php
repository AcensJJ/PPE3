<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommanderController extends AbstractController
{
    /**
     * @Route("/commander", name="commander")
     */
    public function index()
    {
        return $this->render('commander/information.html.twig', [
            'controller_name' => 'Commander',
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
