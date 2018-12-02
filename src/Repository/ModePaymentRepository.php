<?php

namespace App\Repository;

use App\Entity\ModePayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModePayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModePayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModePayment[]    findAll()
 * @method ModePayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModePaymentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModePayment::class);
    }

    // /**
    //  * @return ModePayment[] Returns an array of ModePayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModePayment
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
