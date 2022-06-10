<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Product';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_product';

    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $name = MyTools::getOption($filters, 'name');
        $description = MyTools::getOption($filters, 'description');
        $unit_price = MyTools::getOption($filters, 'unit_price');
     //   $vat = MyTools::getOption($filters, 'vat'); // menech bech naamlou search bihom
        //$unity = MyTools::getOption($filters, 'unity');  // menech bech naamlou search bihom
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');
        $company = MyTools::getOption($filters, 'company');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'name' => 'a.name',

            'prix_ht' => "CONCAT(a.unit_price,' ',cr.shortname)",
            'vat' => "CONCAT(v.value,' ','%')",
            'prix_ttc' => "CONCAT((cast(a.unit_price + ((a.unit_price * v.value)/ 100 )as decimal(10,2))),' ',cr.shortname) ",
          //  'company' => 'c.code',
           // 'currency' => 'cr.shortname',
        //    'created_at' => "TO_CHAR(a.created_at,  'YYYY-MM-DD')",
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
            . ' INNER JOIN bl_company AS c ON (c.id = a.company_id) '
            . ' INNER JOIN bl_country AS ct ON (ct.id = c.country_id) '
            . ' INNER JOIN bl_currency AS cr ON (cr.id = ct.currency_id) '
            . ' INNER JOIN bl_unity AS u ON (u.id = a.unity_id) '
            . ' INNER JOIN bl_vat AS v ON (v.id = a.vat_id) ' ;

        if (!empty($name)) {
            $where[] = ' a.name = :name ';
            $parameters[':name'] =  $name ;
        }
        if (!empty($description)) {
            $where[] = ' a.description = :description ';
            $parameters[':description'] =  $description ;
        }
        if (!empty($unit_price)) {
            $where[] = ' a.unit_price = :unit_price ';
            $parameters[':unit_price'] =  $unit_price ;
        }

        if (!empty($company)) {
            $where[] = '  a.company_id = :company ';
            $parameters[':company'] = $company;
        }

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
    //  * @return Product[] Returns an array of Product objects
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
      public function findOneBySomeField($value): ?Product
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
