<?php

namespace App\Repository;

use App\Entity\KeycloakId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method KeycloakId|null find($id, $lockMode = null, $lockVersion = null)
 * @method KeycloakId|null findOneBy(array $criteria, array $orderBy = null)
 * @method KeycloakId[]    findAll()
 * @method KeycloakId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeycloakIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KeycloakId::class);
    }

    // /**
    //  * @return KeycloakId[] Returns an array of KeycloakId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?KeycloakId
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
