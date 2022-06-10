<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Company';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_company';

    /**
     * Get comapnies by filters
     * @param array filters
     * @return array
     */
    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $name = MyTools::getOption($filters, 'name');
        $registry = MyTools::getOption($filters, 'registry');
        $firstname = MyTools::getOption($filters, 'firstname');
        $lastname = MyTools::getOption($filters, 'lastname');
        $address = MyTools::getOption($filters, 'address');
        $phone = MyTools::getOption($filters, 'phone');
        $website = MyTools::getOption($filters, 'website');
        $zipcode = MyTools::getOption($filters, 'zipcode');
        $city = MyTools::getOption($filters, 'city');

        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'name' => 'a.name',
            'firstname' => 'a.firstname',
            'lastname' => 'a.lastname',
            'address' => 'a.address',
            'phone' => 'a.phone',
            'website' => 'a.website',
            'registry_number' => 'a.registry_number',
            'vat_number' => 'a.vat_number',
            'zipcode' => 'a.zipcode',
            'city' => 'a.city',
            'longname' => "CONCAT(a.name,'-',a.registry_number)",
            'country' => 'c.code',
            'country_longname' => "CONCAT(c.name,'-',c.shortname)",
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

        $sql = 'SELECT  ' . substr($sql, 0, -2) . ' FROM ' . self::TABLE_NAME . ' a '
                . ' INNER JOIN bl_country AS c ON (c.id = a.country_id) ';

        if (!empty($name)) {
            $where[] = ' a.name ILIKE :name ';
            $parameters[':name'] = '%' . $name . '%';
        }

        if (!empty($search)) {
            $where[] = ' ( a.name ILIKE :search OR a.registry_number ILIKE :search ) ';
            $parameters[':search'] = '%' . $search . '%';
        }

        if (!empty($registry)) {
            $where[] = ' a.registry_number ILIKE :registry';
            $parameters[':registry'] = '%' . $registry . '%';
        }

        if (!empty($firstname)) {
            $where[] = ' a.firstname ILIKE :firstname';
            $parameters[':firstname'] = '%' . $firstname . '%';
        }

        if (!empty($lastname)) {
            $where[] = ' a.lastname ILIKE :lastname';
            $parameters[':lastname'] = '%' . $lastname . '%';
        }

        if (!empty($address)) {
            $where[] = ' a.address ILIKE :address';
            $parameters[':address'] = '%' . $address . '%';
        }



        if (!empty($phone)) {
            $where[] = ' a.phone ILIKE :phone';
            $parameters[':phone'] = '%' . $phone . '%';
        }


        if (!empty($website)) {
            $where[] = ' a.website ILIKE :website';
            $parameters[':website'] = '%' . $website . '%';
        }


        if (!empty($zipcode)) {
            $where[] = ' a.zipcode ILIKE :zipcode';
            $parameters[':zipcode'] = '%' . $zipcode . '%';
        }


        if (!empty($city)) {
            $where[] = ' a.city ILIKE :city';
            $parameters[':city'] = '%' . $city . '%';
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
    //  * @return Company[] Returns an array of Company objects
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
      public function findOneBySomeField($value): ?Company
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
