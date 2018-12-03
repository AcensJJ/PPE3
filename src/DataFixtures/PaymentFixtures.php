<?php

namespace App\DataFixtures;

use App\Entity\PaymentOrder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class PaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $product = new PaymentOrder();
        $product->setLibelle('PayPal');
        $manager->persist($product);

        $manager->flush();
    }
}
