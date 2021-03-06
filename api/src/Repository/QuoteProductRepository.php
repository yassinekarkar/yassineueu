<?php

namespace App\Repository;

use App\Entity\QuoteProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method QuoteProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuoteProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuoteProduct[]    findAll()
 * @method QuoteProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteProductRepository extends ServiceEntityRepository
{
    use \App\Traits\RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuoteProduct::class);
    }
    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'QuoteProduct';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_quote_product';

    public function getByFilters($filters = [])
    {
        $quote = MyTools::getOption($filters, 'quote');
        $name = MyTools::getOption($filters, 'name');
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');


        $select = [
           // 'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'name' => 'a.name',
            'product_order' => 'a.porder',
            'unit_price' => 'a.unit_price',
            'amount' => 'a.amount',
            'discount' => 'a.discount',
            'discount_fixed_value' => 'a.discount_fixed_value',
            'vat' => "CONCAT(v.value,' ','%')",
            'unity' => 'u.name',
            'quote_estimate_number' => 'q.estimate_number',
            'created_at' => "TO_CHAR(a.created_at,  'YYYY-MM-DD')",
        ];

        $parameters = [];
        $where = [];
        $sql = '';
        $rsm = new ResultSetMapping();

        foreach ($select as $column => $valuee) {
            $sql .= $valuee . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }

        $sql = 'SELECT  ' . substr($sql, 0, -2) . ' FROM ' . self::TABLE_NAME . ' a '
            . ' INNER JOIN bl_unity AS u ON (u.id = a.unity_id) '
            . ' INNER JOIN bl_vat AS v ON (v.id = a.vat_id) '
            . ' INNER JOIN bl_quote AS q ON (q.id = a.quote_id) '
  // $sql = 'SELECT  ' . substr($sql, 0, -2) . ' FROM ' . self::TABLE_NAME . ' AS qp '
    //   . ' INNER JOIN bl_quote AS q ON ( qp.quote_id = q.id ) '
        ;

        if (!empty($name)) {
            $where[] = ' a.name = :name ';
            $parameters[':name'] =  $name ;
        }


        if (!empty($quote)) {
            $where[] = ' a.quote_id = :quote';
            $parameters[':quote'] =  $quote  ;
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($select[$orderBy])) {
            $sql .= ' ORDER BY ' . $select[$orderBy] . '  ' . $order;
        }


        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameters($parameters)
            ->getResult();
    }

    // /**
    //  * @return QuoteProduct[] Returns an array of QuoteProduct objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuoteProduct
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
