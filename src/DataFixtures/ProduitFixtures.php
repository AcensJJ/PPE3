<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Produit;
use App\Entity\Categorie;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      for($i=1;$i<=6 ;$i++){
          $produit=new Produit;

         $produit->setTitre("T-shirt Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAM0_BJKsv2rWNB2DWxbaHahLjBZH-GImrY4CPpuJ7qa49F81Y")
                 ->setPrix("10$i")
                 ->setStock("1$i");            

         $manager->persist($produit);


      }
        $manager->flush();
    }
}
