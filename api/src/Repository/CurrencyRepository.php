<?php

namespace App\Repository;

use App\Entity\Currency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Currency|null find($id, $lockMode = null, $lockVersion = null)
 * @method Currency|null findOneBy(array $criteria, array $orderBy = null)
 * @method Currency[]    findAll()
 * @method Currency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CurrencyRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Currency';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_currency';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    /**
     * Get currencies by filters
     * @param array filters
     * @return array
     */
    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $name = MyTools::getOption($filters, 'name');
        $shortname = MyTools::getOption($filters, 'shortname');
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'name' => 'a.name',
            'shortname' => 'a.shortname',
            'longname' => "CONCAT(a.name,'-',a.shortname)",
            'created_at' => "TO_CHAR(a.created_at,  'YYYY-MM-DD')",
        ];

        $parameters = [];
        $where = [];
        $sql = '';
        $rsm = new ResultSetMapping();

        foreach ($select as $column => $value) {
            $sql .= $value . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }

        $sql = 'SELECT  ' . substr($sql, 0, -2) . ' FROM ' . self::TABLE_NAME . ' a ';

        if (!empty($name)) {
            $where[] = ' a.name ILIKE :name ';
            $parameters[':name'] = '%' . $name . '%';
        }

        if (!empty($shortname)) {
            $where[] = ' a.shortname ILIKE :shortname';
            $parameters[':shortname'] = '%' . $shortname . '%';
        }

        if (!empty($search)) {
            $where[] = ' (a.shortname ILIKE :search OR a.name ILIKE :search )';
            $parameters[':search'] = '%' . $search . '%';
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
    //  * @return Currency[] Returns an array of Currency objects
    //  */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('c.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Currency
      {
      return $this->createQueryBuilder('c')
      ->andWhere('c.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
