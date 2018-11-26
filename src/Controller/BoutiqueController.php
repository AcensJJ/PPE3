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
        $repo=$this->getDoctrine()->getRepository(Produit::class);
        $produits = $repo->createQueryBuilder('c')
                            ->setMaxResults(3)
                            ->getQuery()
                            ->execute();
        
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController',
            'produits' => $produits, 
        ]);
        
    }

    /**
     * @Route("/produit", name="produit")
     */
    public function produit(){

        $repo=$this->getDoctrine()->getRepository(Produit::class);
        $produit=$repo->findAll();

        return $this->render('boutique/produit.html.twig',[
            'controller_name'=> 'Les articles',
            'produit' => $produit,
            ]);

    }

    /**
     * @Route("/article/{id}", name="article")
     */
    public function article(Produit $article){

        return $this->render('boutique/article.html.twig',[
            'controller_name'=> 'Un article',
            'article' => $article,
            ]);

    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(){

        return $this->render('boutique/panier.html.twig',[
            'controller_name'=> 'Panier',
            ]);

    }
  
}
