<?php

namespace App\Repository;

use App\Entity\TipoCuitCuil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoCuitCuil|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoCuitCuil|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoCuitCuil[]    findAll()
 * @method TipoCuitCuil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoCuitCuilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoCuitCuil::class);
    }

    // /**
    //  * @return TipoCuitCuil[] Returns an array of TipoCuitCuil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TipoCuitCuil
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
