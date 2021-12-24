<?php

namespace App\Repository;

use App\Entity\Admision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Admision|null find($id, $lockMode = null, $lockVersion = null)
 * @method Admision|null findOneBy(array $criteria, array $orderBy = null)
 * @method Admision[]    findAll()
 * @method Admision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdmisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Admision::class);
    }

    // /**
    //  * @return Admision[] Returns an array of Admision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Admision
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
