<?php

namespace App\Repository;

use App\Entity\PersonaJuridica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonaJuridica|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonaJuridica|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonaJuridica[]    findAll()
 * @method PersonaJuridica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaJuridicaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonaJuridica::class);
    }

    // /**
    //  * @return PersonaJuridica[] Returns an array of PersonaJuridica objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PersonaJuridica
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
