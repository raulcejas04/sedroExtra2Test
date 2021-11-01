<?php

namespace App\Repository;

use App\Entity\Dispositivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dispositivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dispositivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dispositivo[]    findAll()
 * @method Dispositivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispositivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispositivo::class);
    }

    // /**
    //  * @return Dispositivo[] Returns an array of Dispositivo objects
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
    public function findOneBySomeField($value): ?Dispositivo
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
