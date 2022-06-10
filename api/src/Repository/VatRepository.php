<?php

namespace App\Repository;

use App\Entity\Vat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Vat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vat[]    findAll()
 * @method Vat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VatRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vat::class);
    }


    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Vat';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_vat';


    /**
     * Get currencies by filters
     * @param array filters
     * @return array
     */
    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $value = MyTools::getOption($filters, 'value');
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');
        $company = MyTools::getOption($filters, 'company');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'value' => 'a.value',
            'is_default' => 'a.is_default',
            'company' => 'c.code',
            'longname' => "CONCAT(a.value,' ','%')",
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
            . ' INNER JOIN bl_company AS c ON (c.id = a.company_id) ';

        if (!empty($value)) {
            $where[] = ' a.value = :value ';
            $parameters[':value'] =  $value ;
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











}
