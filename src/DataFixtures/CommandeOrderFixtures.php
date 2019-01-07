<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\PaymentOrder;
use App\Entity\CommandeOrder;
use App\Entity\IdentityOrder;
use App\Entity\LivraisonOrder;
use App\AJ\CommandeBundle\UniqRef;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ProduitFixtures;
use App\DataFixtures\ModeLivraisonFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommandeOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for($i=1;$i<=3 ;$i++){

            $paymentOrder = new PaymentOrder();
            $paymentOrder->setPaymentId("Pour le Paypal");
            $paymentOrder->setStatus("approved");
            $paymentOrder->setAmount("100");
            $paymentOrder->setCurrency("EUR");
            $paymentOrder->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
            $manager->persist($paymentOrder);
        
            //Création IdentityOrder
            $identityOrder = new IdentityOrder();
            $identityOrder->setPrenom("AAA")
                            ->setNom("aaa")
                            ->setEmail("aaa@aaa.fr")
                            ->setNumTel("0000000000");
            $manager->persist($identityOrder);

            //Création LivraisonOrder
            $livraisonOrder = new LivraisonOrder();
            $livraisonOrder->setAdresse("00 rue test")
                            ->setPays("France")
                            ->setVille("Perpignan")
                            ->setCodePostal("66000");
            $manager->persist($livraisonOrder);

            //Création de la commande
            $ref = new UniqRef();
            $reference = $ref->generateRef();
            $dateNow = date('Y-m-d H:i:s');

            $user = $this->getReference('admin');
            $produit = $this->getReference('pantalon');
            $produit2 = $this->getReference('pantalon2');
            $modeLivraison = $this->getReference('free');
            $commande = new CommandeOrder();
            $commande->setReference($reference)
                        ->setStatus('En attente')
                        ->setDate(\DateTime::createFromFormat('Y-m-d H:i:s', $dateNow))
                        ->setUser($user)
                        ->setLivraison($livraisonOrder)
                        ->setIdentity($identityOrder)
                        ->setPaymentOrder($paymentOrder)
                        ->setModeLivraison($modeLivraison)
                        ->addProduit($produit)
                        ->addProduit($produit2);
            
            $manager->persist($commande);
            }

            $manager->flush();
    }

    // Doit charger le fichier avant celui ci, pour les reference
    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ProduitFixtures::class,
            ModeLivraisonFixtures::class,
        );
    }
}