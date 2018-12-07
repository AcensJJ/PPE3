<?php

namespace App\DataFixtures;

use App\Entity\ModeLivraison;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ModeLivraisonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $free = new ModeLivraison();
        $free->setMode('standard')
             ->setPrix('0')
             ->setDescription('Livraison sous 1 semaine');
        $manager->persist($free);

        $fast = new ModeLivraison();
        $fast->setMode('fast')
             ->setPrix('10')
             ->setDescription('Livraison rapide sous 2 semaine');
        $manager->persist($fast);

        $manager->flush();
    }
}
