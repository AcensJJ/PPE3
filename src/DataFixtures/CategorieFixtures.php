<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $Tshirt = new Categorie();
        $Tshirt->setLibelle('T-shirt');
        $manager->persist($Tshirt);

        $Pull = new Categorie();
        $Pull->setLibelle('Pull');
        $manager->persist($Pull);

        $Veste = new Categorie();
        $Veste->setLibelle('Veste');
        $manager->persist($Veste);

        $Pantalon = new Categorie();
        $Pantalon->setLibelle('Pantalon');
        $manager->persist($Pantalon);

        $Short = new Categorie();
        $Short->setLibelle('Short');
        $manager->persist($Short);

        $manager->flush();

        // reference pour acceder avec une autre fixture
        $this->addReference('Tshirt', $Tshirt);
        $this->addReference('Pull', $Pull);
        $this->addReference('Veste', $Veste);
        $this->addReference('Pantalon', $Pantalon);
        $this->addReference('Short', $Short);
    }
}
