<?php

namespace App\Repository;

use App\Entity\BackUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackUser[]    findAll()
 * @method BackUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackUser::class);
    }

    // /**
    //  * @return BackUser[] Returns an array of BackUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BackUser
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
