<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categorie = new Categorie();

        $categorie->setLibelle('T-shirt');
        $manager->persist($categorie);

        $categorie = new Categorie();

        $categorie->setLibelle('Pull');
        $manager->persist($categorie);

        $categorie = new Categorie();

        $categorie->setLibelle('Veste');
        $manager->persist($categorie);

        $categorie = new Categorie();

        $categorie->setLibelle('Pantalon');
        $manager->persist($categorie);

        $categorie = new Categorie();

        $categorie->setLibelle('Short');
        $manager->persist($categorie);

        $manager->flush();
    }
}
