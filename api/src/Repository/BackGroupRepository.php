<?php

namespace App\Repository;

use App\Entity\BackGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackGroup[]    findAll()
 * @method BackGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackGroup::class);
    }

    // /**
    //  * @return BackGroup[] Returns an array of BackGroup objects
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
    public function findOneBySomeField($value): ?BackGroup
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
