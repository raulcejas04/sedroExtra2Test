<?php

namespace App\Repository;

use App\Entity\DispositivoResponsable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DispositivoResponsable|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispositivoResponsable|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispositivoResponsable[]    findAll()
 * @method DispositivoResponsable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispositivoResponsableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispositivoResponsable::class);
    }

    // /**
    //  * @return DispositivoResponsable[] Returns an array of DispositivoResponsable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DispositivoResponsable
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
