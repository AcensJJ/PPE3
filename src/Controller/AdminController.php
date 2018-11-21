<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{
   /**
     * @Route("/admin", name="admin")
     */
    public function Admin(){

        return $this->render('admin/home.html.twig',['controller_name'=> 'AdminController']);


    }


      /**
     * @Route("/client", name="client")
     */
    public function afficheclient(){

        return $this->render('admin/client.html.twig',['controller_name'=> 'AdminController']);


    }



        /**
     * @Route("/commande", name="commande")
     */
    public function affichecommande(){

        return $this->render('admin/commande.html.twig',['controller_name'=> 'AdminController']);


    }

}