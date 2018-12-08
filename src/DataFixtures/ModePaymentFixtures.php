<?php

namespace App\DataFixtures;

use App\Entity\ModePayment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ModePaymentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $paypal = new ModePayment();
        $paypal->setLibelle('PayPal')
                ->setImage('http://www.websuccessstories.com/wp-content/uploads/2015/03/Paypal.jpg');
        $manager->persist($paypal);

        $manager->flush();
    }
}
