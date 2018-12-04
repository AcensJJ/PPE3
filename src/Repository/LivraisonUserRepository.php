<?php

namespace App\Repository;

use App\Entity\LivraisonUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LivraisonUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method LivraisonUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method LivraisonUser[]    findAll()
 * @method LivraisonUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LivraisonUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LivraisonUser::class);
    }

    // /**
    //  * @return LivraisonUser[] Returns an array of LivraisonUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LivraisonUser
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
