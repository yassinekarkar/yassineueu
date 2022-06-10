<?php

namespace App\Repository;

use App\Entity\Facture;
use App\Entity\QuoteProduct;
use App\Entity\QuoteConfig;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }
    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Facture';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_facture';



    public function getByFilters($filters)
    {
        $valid = MyTools::getOption($filters, 'valid');
        $active = MyTools::getOption($filters, 'active');
        $code = MyTools::getOption($filters, 'code');
        $label = MyTools::getOption($filters, 'label');
        $createdAt = MyTools::getOption($filters, 'created_at');
        $page = MyTools::getOption($filters, 'index');
        $maxPerPage = MyTools::getOption($filters, 'size');
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'f.code',
            'status' => 'f.status',
            'client_name' => 'f.client_name',
            'created_at' => "TO_CHAR(f.created_at,  'YYYY-MM-DD')",
            'total_price_ht' => "CONCAT(cast((fp.total_pr)as decimal(10,2)),' ',cr.shortname) ",
       //     'total_price_ttc' => 'cast((fp.total_prTTC)as decimal(10,2))',
            'total_price_ttc' => "CONCAT(cast((fp.total_prTTC*(1-( f.discount_total/100)))as decimal(10,2)),' ',cr.shortname) ",
            'total_products' => 'fp.total_p'
        ];



        $parameters = [];
        $where = [];
        $sql = '';
        $rsm = new ResultSetMapping();

        foreach ($select as $column => $value) {
            $sql .= $value . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }


        $sql = 'SELECT  ' . substr($sql, 0, -2)
            . ' FROM ' . self::TABLE_NAME . ' AS f '
            //  . 'INNER JOIN bl_quote_product qp ON qp.quote_id = q.id '
            . " INNER JOIN (
            SELECT fp.facture_id,
            
            SUM(((fp.unit_price * (1-(fp.discount/100))) + ((fp.vat_value / 100) * fp.unit_price)) * fp.amount) AS total_pr,
            SUM((fp.unit_price + ((fp.vat_value / 100) * fp.unit_price)) * fp.amount) AS total_prTTC  ,
            COUNT(fp.id) As total_p 
            FROM bl_facture_product fp GROUP BY fp.facture_id) AS fp ON (fp.facture_id = f.id)"
            . ' INNER JOIN bl_currency AS cr ON (cr.id = f.currency_id) '
        ;





        if (!empty($code)) {
            $where[] = ' q.code ILIKE :code';
            $parameters[':code'] = '%' . $code . '%';
        }


        if (!empty($createdAt)) {

            $date = new \DateTime($createdAt);

            $parameters[':datec1'] = $date->format('Y-m-d') . ' 00:00:00';
            $where[] = ' f.created_at >= :datec1';

            $parameters[':datec2'] = $date->format('Y-m-d') . ' 23:59:59';
            $where[] = ' f.created_at <= :datec2';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        // $sql .=' GROUP BY q.id ';
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
    //  * @return Quote[] Returns an array of Quote objects
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
    public function findOneBySomeField($value): ?Quote
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    /*
    /**
     * Get products
     * @param array filters
     * @return array
     */
    /*
    public function getquote($data)
    {
        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'estimate_number' => 'a.estimate_number',
            'status' => 'a.status',
            'pre_note' => 'a.pre_note',
            'date_begin' => 'a.date_begin',
            'date_end' => 'a.date_end',
            'discount' => 'a.discount',
            'creator' => 'u.firstname',
            'company' => 'c.code',
            'company_mail' => 'c.mail',
            'company_name' => 'c.name',
            'company_address' => 'c.address',
            'company_zipcode' => 'c.zipcode',
            'company_city' => 'c.city',
            'client' => 'cl.code',
            'client_name' => 'cl.name',
            'client_address' => 'cl.address',
            'client_zipcode' => 'cl.zipcode',
            'client_city' => 'cl.city',

            'head' => 'cf.head',
            'total_line' => 'cf.total_line',
            'discount_config' => 'cf.discount',
            'discount_on_total' => 'cf.discount_on_total',
            'discount_fixed_value' => 'cf.discount_on_total',
            'discount_base_ttc' => 'cf.discount_base_ttc',
            'language'=> 'ln.name',
            'currency_name' => 'cr.name',
            'currency_shortname' => 'cr.shortname',

            'product_name' => 'pr.name',
            'product_order' => 'pr.porder',
            'product_unit_price' => 'pr.unit_price',
            'product_date' => 'pr.pdate',
            'product_discount' => 'pr.discount',
            'unity_name' => 'un.name',
            'vat_value' => 'va.value',

        ];



        $parameters = [];
        $sql = '';
        $rsm = new ResultSetMapping();
        $where = [];




        foreach ($select as $column => $value) {
            $sql .= $value . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }


        $sql = 'SELECT  ' . substr($sql, 0, -2) . ' FROM ' . self::TABLE_NAME . ' AS a '
            . ' INNER JOIN bl_company AS c ON (c.id = a.company_id) '
            . ' INNER JOIN bl_client AS cl ON (cl.id = a.client_id) '
            . ' INNER JOIN bl_user AS u ON (u.id = a.creator_id) '
           // . ' INNER JOIN bl_quote_config AS cf ON (cf.quote_id = a.id) '
         //   . ' INNER JOIN bl_language AS ln ON (ln.id = cf.language_id) '
         //   . ' INNER JOIN bl_currency AS cr ON (cr.id = cf.currency_id) '
         //   . ' INNER JOIN bl_quote_product AS pr ON (pr.quote_id = a.id) '
           // . ' INNER JOIN bl_unity AS un ON (un.id = pr.unity_id) '
          //  . ' INNER JOIN bl_vat AS va ON (va.id = pr.vat_id) ';
        ;

        $res = $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameters($parameters)
            ->getResult();

        $data = [];
        $r = null;
        foreach ($res as $re){

            $r['quote'] = array(
                'total' => $re['total'],
                'code' => $re['code'],
                'estimate_number' => $re['estimate_number'],
                'status' => $re['status'],
                'pre_note' => $re['pre_note'],
                'date_begin' => $re['date_begin'],
                'date_end' => $re['date_end'],
                'discount' => $re['discount'],
                'creator' => $re['creator'],
                'company' => $re['company'],
                'company_mail' => $re['company_mail'],
                'company_name' => $re['company_name'],
                'company_address' => $re['company_address'],
                'company_zipcode' => $re['company_zipcode'],
                'company_city' => $re['company_city'],
                'client' => $re['client'],
                'client_name' => $re['client_name'],
                'client_address' => $re['client_address'],
                'client_zipcode' => $re['client_zipcode'],
                'client_city' => $re['client_city'],
            );

            $r['config'] = array(
                'head' => $re['head'],
                'total_line' => $re['total_line'],
                'discount_config' => $re['discount_config'],
                'discount_on_total' => $re['discount_on_total'],
                'discount_fixed_value' => $re['discount_fixed_value'],
                'discount_base_ttc' => $re['discount_base_ttc'],
                'language'=> $re['language'],
                'currency_name' => $re['currency_name'],
                'currency_shortname' => $re['currency_shortname'],
            );

            $r['product'] = array(
                'product_name' => $re['product_name'],
                'product_order' => $re['product_order'],
                'product_unit_price' => $re['product_unit_price'],
                'product_date' => $re['product_date'],
                'product_discount' => $re['product_discount'],
                'unity_name' => $re['unity_name'],
                'vat_value' => $re['vat_value'],
            );
            array_push($data,$r);
        }

        return $data;
    }
    */

    public function getDateEnd($filters)
    {
        $valid = MyTools::getOption($filters, 'valid');
        $active = MyTools::getOption($filters, 'active');
        $code = MyTools::getOption($filters, 'code');
        $label = MyTools::getOption($filters, 'label');
        $createdAt = MyTools::getOption($filters, 'created_at');
        $page = MyTools::getOption($filters, 'index');
        $maxPerPage = MyTools::getOption($filters, 'size');
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');

        $select = [

        ];



        $parameters = [];
        $where = [];
        $sql = '';
        $rsm = new ResultSetMapping();

        foreach ($select as $column => $value) {
            $sql .= $value . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }


        $sql = 'SELECT  ' . substr($sql, 0, -2)
            . ' FROM ' . self::TABLE_NAME . ' AS f '
            //  . 'INNER JOIN bl_quote_product qp ON qp.quote_id = q.id '
         . 'SELECT DATEADD(day, 5, f.invoice_date) AS DateAdd'

        ;





        if (!empty($code)) {
            $where[] = ' q.code ILIKE :code';
            $parameters[':code'] = '%' . $code . '%';
        }


        if (!empty($createdAt)) {

            $date = new \DateTime($createdAt);

            $parameters[':datec1'] = $date->format('Y-m-d') . ' 00:00:00';
            $where[] = ' f.created_at >= :datec1';

            $parameters[':datec2'] = $date->format('Y-m-d') . ' 23:59:59';
            $where[] = ' f.created_at <= :datec2';
        }

        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        // $sql .=' GROUP BY q.id ';
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




}


