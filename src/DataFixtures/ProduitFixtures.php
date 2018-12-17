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
      for($i=1;$i<=3 ;$i++){

        $tshirt=new Produit;
        $cate = $this->getReference('Tshirt');
        $tshirt->setTitre("T-shirt Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQAM0_BJKsv2rWNB2DWxbaHahLjBZH-GImrY4CPpuJ7qa49F81Y")
                 ->setPrix("10$i")
                 ->setCategorieProduit($cate);          
        $manager->persist($tshirt);
      }

      for($i=1;$i<=3 ;$i++){

        $pull=new Produit;
        $cate = $this->getReference('Pull');
        $pull->setTitre("Pull Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://images-na.ssl-images-amazon.com/images/I/81jNqMIbRCL._SY355_.jpg")
                 ->setPrix("10$i")
                 ->setCategorieProduit($cate);          
        $manager->persist($pull);
      }

      for($i=1;$i<=3 ;$i++){

        $short=new Produit;
        $cate = $this->getReference('Short');
        $short->setTitre("Short Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://cdn.leslipfrancais.fr/1921-thickbox_default/le-capitaine-short-de-bain-bleu-marine.jpg")
                 ->setPrix("10$i")
                 ->setCategorieProduit($cate);          
        $manager->persist($short);
      }

      for($i=1;$i<=3 ;$i++){

        $veste=new Produit;
        $cate = $this->getReference('Veste');
        $veste->setTitre("Veste Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://ol-boutique-cdn-2.azureedge.net/17481-large_default/veste-anthem-ol-adulte-noir-2018-19.jpg")
                 ->setPrix("10$i")
                 ->setCategorieProduit($cate);          
        $manager->persist($veste);
      }

      for($i=1;$i<=3 ;$i++){

        $pantalon=new Produit;
        $cate = $this->getReference('Pantalon');
        $pantalon->setTitre("Pantalon Collection n°$i")
                 ->setDescription("Edition Speciale")
                 ->setImage("https://ol-boutique-cdn-3.azureedge.net/16780-home_default/pantalon-34-entrainement-olympique-lyonnais-adulte-noir-20182019.jpg")
                 ->setPrix("10$i")
                 ->setCategorieProduit($cate);          
        $manager->persist($pantalon);
      }


        $manager->flush();
    }
}
