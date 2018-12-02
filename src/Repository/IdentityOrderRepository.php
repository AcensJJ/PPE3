<?php

namespace App\Repository;

use App\Entity\IdentityOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method IdentityOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method IdentityOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method IdentityOrder[]    findAll()
 * @method IdentityOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IdentityOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IdentityOrder::class);
    }

    // /**
    //  * @return IdentityOrder[] Returns an array of IdentityOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IdentityOrder
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
