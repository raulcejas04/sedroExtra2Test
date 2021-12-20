<?php

namespace App\Repository;

use App\Entity\Invitacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Invitacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitacion[]    findAll()
 * @method Invitacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvitacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitacion::class);
    }

    // /**
    //  * @return Invitacion[] Returns an array of Invitacion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invitacion
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findInvitacionesByUser($user)
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.dispositivo', 'd', Join::WITH, 'd.fechaEliminacion IS NULL')
            ->leftJoin('d.personaJuridica', 'pj', Join::WITH, 'pj.fechaEliminacion IS NULL')
            ->leftJoin('pj.representaciones', 'r', Join::WITH, 'r.fechaEliminacion IS NULL')
            ->andWhere('r.personaFisica = :pf OR i.origen = :pf')
            ->setParameters([
                "pf" => $user->getPersonaFisica()
            ])
            ->getQuery()
            ->getResult();
    }
}
