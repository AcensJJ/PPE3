<?php

namespace App\Repository;

use App\Entity\FactureUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FactureUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureUser[]    findAll()
 * @method FactureUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FactureUser::class);
    }

    // /**
    //  * @return FactureUser[] Returns an array of FactureUser objects
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
    public function findOneBySomeField($value): ?FactureUser
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
