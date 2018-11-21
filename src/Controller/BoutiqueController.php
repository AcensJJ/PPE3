<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

class BoutiqueController extends AbstractController
{
    /**
     * @Route("/", name="boutique")
     */
    public function index()
    {
        
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            
        ]);
    }

 /**
     * @Route("/produit", name="produit")
     */
    public function article(){
        $repo=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repo->findAll();
        return $this->render('boutique/produit.html.twig',['controller_name'=> 'BoutiqueController','produit'=>$produit]);


    }



  
}
