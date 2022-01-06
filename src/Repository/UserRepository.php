<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findByUsernameOrEmail($username, $email)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username OR u.email = :email')
            ->andWhere('u.fechaEliminacion IS NULL')
            ->setParameters(['username' => $username, 'email' => $email])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByUserUsernameAndRealm($username, $realm)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :username')
            ->andWhere('u.realm = :realm')
            ->andWhere('u.fechaEliminacion IS NULL')
            ->setParameters(['username' => $username, 'realm' => $realm])
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getAllSuperAdminUsers()
    {
        return $this->createQueryBuilder('u')
            ->join('u.userGroups', 'ug')
            ->join('ug.grupo', 'g')
            ->where('g.nombre = :nombre')
            ->setParameter('nombre', 'SuperAdministradores')
            ->getQuery()
            ->getResult();    
    }

    // /**
    //  * @return User[] Returns an array of User objects
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
    public function findOneBySomeField($value): ?User
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
