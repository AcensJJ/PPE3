<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ModeLivraisonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setMode('Gratuit')
                ->setPrix('0');
        $manager->persist($product);

        $manager->flush();
    }
}
