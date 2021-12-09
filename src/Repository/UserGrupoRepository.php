<?php

namespace App\Repository;

use App\Entity\UserGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGrupo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGrupo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGrupo[]    findAll()
 * @method UserGrupo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGrupo::class);
    }

    // /**
    //  * @return UserGrupo[] Returns an array of UserGrupo objects
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
    public function findOneBySomeField($value): ?UserGrupo
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
