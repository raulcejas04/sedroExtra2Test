<?php

namespace App\Repository;

use App\Entity\Sexo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sexo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sexo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sexo[]    findAll()
 * @method Sexo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SexoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sexo::class);
    }

    // /**
    //  * @return Sexo[] Returns an array of Sexo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sexo
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
