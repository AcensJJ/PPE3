<?php

namespace App\Repository;

use App\Entity\FactureOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FactureOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureOrder[]    findAll()
 * @method FactureOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FactureOrder::class);
    }

    // /**
    //  * @return FactureOrder[] Returns an array of FactureOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FactureOrder
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
