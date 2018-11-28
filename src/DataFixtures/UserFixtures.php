<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername('admin')
              ->setEmail('admin@admin.fr')
              ->setEnabled('1')
              ->setRoles(array('ROLE_ADMIN'));
        $password = $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('user')
             ->setEnabled('1')
             ->setEmail('user@user.fr');
        $password = $this->encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();

        // reference pour acceder avec une autre fixture
        $this->addReference('admin', $admin);
        $this->addReference('user', $user);
    }
}
