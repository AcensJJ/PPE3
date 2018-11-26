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
        $panier = new Panier();
        $user = $this->getReference('admin');
        $panier->setIDUser($user);
        $manager->persist($panier);

        $panier = new Panier();
        $user = $this->getReference('user');
        $panier->setIDUser($user);
        $manager->persist($panier);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
