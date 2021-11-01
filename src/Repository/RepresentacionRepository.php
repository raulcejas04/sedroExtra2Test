<?php

namespace App\Repository;

use App\Entity\Representacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Representacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Representacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Representacion[]    findAll()
 * @method Representacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepresentacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Representacion::class);
    }

    // /**
    //  * @return Representacion[] Returns an array of Representacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Representacion
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
