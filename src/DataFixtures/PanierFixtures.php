<?php

namespace App\DataFixtures;

use App\Entity\Panier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\UserFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PanierFixtures extends Fixture  implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $panier1 = new Panier();
        $user = $this->getReference('admin');
        $panier1->setUser($user);
        $manager->persist($panier1);

        $panier2 = new Panier();
        $user = $this->getReference('user');
        $panier2->setUser($user);
        $manager->persist($panier2);

        $manager->flush();
    }

    // Doit charger le fichier avant celui ci, pour les reference
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
