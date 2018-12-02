<?php

namespace App\Repository;

use App\Entity\CommandeOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandeOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeOrder[]    findAll()
 * @method CommandeOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandeOrder::class);
    }

    // /**
    //  * @return CommandeOrder[] Returns an array of CommandeOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandeOrder
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
