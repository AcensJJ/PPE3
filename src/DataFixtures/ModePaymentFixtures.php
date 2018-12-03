<?php

namespace App\DataFixtures;

use App\Entity\ModePayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ModePaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new ModePayment();
        $product->setLibelle('PayPal');
        $manager->persist($product);

        $manager->flush();
    }
}
