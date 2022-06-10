<?php

namespace App\Repository;

use App\Entity\QuoteConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method QuoteConfig|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuoteConfig|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuoteConfig[]    findAll()
 * @method QuoteConfig[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteConfigRepository extends ServiceEntityRepository
{
    use \App\Traits\RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuoteConfig::class);
    }


    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'QuoteConfig';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_quote_config';

    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'head' => 'a.head',
            'total_line' => 'a.total_line',
            'discount' => 'a.discount',
            'discount_on_total' => 'a.discount_on_total',
            'discount_fixed_value' => 'a.discount_fixed_value',
            'discount_base_ttc' => 'a.discount_base_ttc',
            'quote_estimate_number' => 'q.estimate_number',
            'language' => 'l.name',
            'currency' => 'c.name'
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
            . ' INNER JOIN bl_quote AS q ON (q.id = a.quote_id) '
            . ' INNER JOIN bl_language AS l ON (l.id = a.language_id) '
            . ' INNER JOIN bl_currency AS c ON (c.id = a.currency_id) ' ;


        if (!empty($search)) {
            $where[] = ' ( a.value = :search )';
            $parameters[':search'] =  $search  ;
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (isset($select[$orderBy])) {
            $sql .= ' ORDER BY ' . $select[$orderBy] . '  ' . $order;
        }

        if ($page > 0) {
            $sql .= ' LIMIT ' . $maxPerPage . ' OFFSET ' . (($page - 1) * $maxPerPage);
        }

        return $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameters($parameters)
            ->getResult();
    }

    // /**
    //  * @return QuoteConfig[] Returns an array of QuoteConfig objects
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
    public function findOneBySomeField($value): ?QuoteConfig
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
