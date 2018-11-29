<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produit;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function article(Produit $article, Request $request, UserInterface $user = null, ObjectManager $manager){

        //IF AJAX ADD PANIER
        if($request->isXmlHttpRequest()){
            //SAVOIR SI L'ARTICLE EXISTE DEJA DANS LA COLLECTION DU PANIER DE L'USER
            $thisPanier = $user->getPanier();
            $thisPanier->addArticle($article);
            $manager->persist($thisPanier);
            $manager->flush();
            $newCountPanier = $thisPanier->getArticles()->count();
            
            $response = new JsonResponse(array("response" => 1, "newCountPanier" => $newCountPanier));
            return $response;
        }

        return $this->render('boutique/article.html.twig',[
            'controller_name'=> 'Un article',
            'article' => $article,
            ]);
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier(UserInterface $user, Request $request, ObjectManager $manager)
    {
        $thisPanier = $this->getDoctrine()
                         ->getRepository(Panier::class)
                         ->createQueryBuilder('c')
                         ->where('c.user = :user')
                         ->setParameter('user', $user)
                         ->setMaxResults(1)
                         ->getQuery()
                         ->getSingleResult();

        //IF AJAX REMOVE ARTICLE PANIER
        if($request->isXmlHttpRequest()){
            $idArticle = $request->request->get('id');
            $article = $this->getDoctrine()
                            ->getRepository(Produit::class)
                            ->find($idArticle);
            $thisPanier->removeArticle($article);
            $manager->persist($thisPanier);
            $manager->flush();

            $response = new JsonResponse("1");
            return $response;
        }

        $thisPanier = $thisPanier->getArticles();
        
        return $this->render('boutique/panier.html.twig', [
            'controller_name' => 'Mon panier',
            'articlesPanier' => $thisPanier,
            ]);          
    }

}
