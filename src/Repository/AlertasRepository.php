<?php

namespace App\Repository;

use App\Entity\Alertas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alertas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alertas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alertas[]    findAll()
 * @method Alertas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alertas::class);
    }

    // /**
    //  * @return Alertas[] Returns an array of Alertas objects
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
    public function findOneBySomeField($value): ?Alertas
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
