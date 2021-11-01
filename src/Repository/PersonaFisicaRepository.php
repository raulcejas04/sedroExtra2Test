<?php

namespace App\Repository;

use App\Entity\PersonaFisica;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonaFisica|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonaFisica|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonaFisica[]    findAll()
 * @method PersonaFisica[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaFisicaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonaFisica::class);
    }

    // /**
    //  * @return PersonaFisica[] Returns an array of PersonaFisica objects
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
    public function findOneBySomeField($value): ?PersonaFisica
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
