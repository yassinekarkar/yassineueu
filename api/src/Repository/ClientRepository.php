<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;
use App\Manager\AbstractManager;
/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Client';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_client';

    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $name = MyTools::getOption($filters, 'name');
        $firstname = MyTools::getOption($filters, 'firstname');
       // $reference = MyTools::getOption($filters, 'reference');
        $lastname = MyTools::getOption($filters, 'lastname');
        $registry_number = MyTools::getOption($filters, 'registry_number');
        $vat_number = MyTools::getOption($filters, 'vat_number');
        $address = MyTools::getOption($filters, 'name');
        $zipcode = MyTools::getOption($filters, 'name');
        $city = MyTools::getOption($filters, 'city');
        $mail = MyTools::getOption($filters, 'mail');
        $phone = MyTools::getOption($filters, 'phone');
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
            'prix' => 'fp.total_pr'

           /* 'type' => 'a.type',
            'firstname' => 'a.firstname',
            'reference' => 'a.reference',
            'lastname' => 'a.lastname',
            'registryNumber' => 'a.registry_number',
            'vatNumber' => 'a.vat_number',
            'address' => 'a.address',
            'zipcode' => 'a.zipcode',
            'city' => 'a.city',
            'mail' => 'a.mail',
            'phone' => 'a.phone',
            'country' => 'ct.name',
            'payment_condition' => 'p.value',
            'longname' => "CONCAT(p.value,' ','jours')",
            'ebill_identifier' => 'a.ebill_identifier',
            'ebill_type' => 'a.ebill_type',
            'company' => 'c.code',
            'currency' => 'cr.shortname',
            'created_at' => "TO_CHAR(a.created_at,  'YYYY-MM-DD')",
           */
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
            . ' INNER JOIN bl_facture AS f ON (a.id = f.client_id) '
            . " INNER JOIN (
            SELECT fp.facture_id,
          

            SUM((((fp.unit_price * (1-(fp.discount/100))) + ((fp.vat_value / 100) * fp.unit_price)) * fp.amount) )AS total_pr
            
            FROM bl_facture_product fp GROUP BY fp.facture_id) AS fp ON (fp.facture_id = f.id)"

        ;


        if (!empty($name)) {
            $where[] = ' a.name = :name ';
            $parameters[':name'] =  $name ;
        }
        if (!empty($firstname)) {
            $where[] = ' a.firstname = :firstname ';
            $parameters[':firstname'] =  $firstname ;
        }
        /*if (!empty($reference)) {
            $where[] = ' a.reference = :reference ';
            $parameters[':reference'] =  $reference ;
        }*/
        if (!empty($lastname)) {
            $where[] = ' a.lastname = :lastname ';
            $parameters[':lastname'] =  $lastname ;
        }
        if (!empty($registry_number)) {
            $where[] = ' a.registry_number = :registry_number ';
            $parameters[':registry_number'] =  $registry_number ;
        }
        if (!empty($vat_number)) {
            $where[] = ' a.vat_number = :vat_number ';
            $parameters[':vat_number'] =  $vat_number ;
        }
        if (!empty($address)) {
            $where[] = ' a.address = :address ';
            $parameters[':address'] =  $address ;
        }
        if (!empty($zipcode)) {
            $where[] = ' a.zipcode = :zipcode ';
            $parameters[':zipcode'] =  $zipcode ;
        }
        if (!empty($city)) {
            $where[] = ' a.city = :city ';
            $parameters[':city'] =  $city ;
        }
        if (!empty($mail)) {
            $where[] = ' a.mail = :mail ';
            $parameters[':mail'] =  $mail ;
        }
        if (!empty($phone)) {
            $where[] = ' a.phone = :phone ';
            $parameters[':phone'] =  $phone ;
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
    //  * @return Client[] Returns an array of Client objects
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
      public function findOneBySomeField($value): ?Client
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
