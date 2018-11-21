<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Produit;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      for($i=1;$i<=10 ;$i++){
          $produit=new Produit;

         $produit->setTitre(" Voiture  n°$i")
                 ->setDescription("Audi R8 ")
                 ->setImage("https://cdn.pixabay.com/photo/2014/09/07/22/34/car-race-438467_960_720.jpg")
                 ->setPrix("100000$i");

         $manager->persist($produit);


      }
        $manager->flush();
    }
}
