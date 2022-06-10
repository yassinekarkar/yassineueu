<?php

namespace App\Repository;

use App\Entity\TaxValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TaxValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxValue[]    findAll()
 * @method TaxValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxValueRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxValue::class);
    }

    // /**
    //  * @return TaxValue[] Returns an array of TaxValue objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('t.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?TaxValue
      {
      return $this->createQueryBuilder('t')
      ->andWhere('t.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
