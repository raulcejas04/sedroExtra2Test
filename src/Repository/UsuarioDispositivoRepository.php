<?php

namespace App\Repository;

use App\Entity\UsuarioDispositivo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UsuarioDispositivo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UsuarioDispositivo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UsuarioDispositivo[]    findAll()
 * @method UsuarioDispositivo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsuarioDispositivoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsuarioDispositivo::class);
    }

    // /**
    //  * @return UsuarioDispositivo[] Returns an array of UsuarioDispositivo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UsuarioDispositivo
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
