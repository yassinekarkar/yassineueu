<?php

namespace App\Repository;

use App\Entity\Iban;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Iban|null find($id, $lockMode = null, $lockVersion = null)
 * @method Iban|null findOneBy(array $criteria, array $orderBy = null)
 * @method Iban[]    findAll()
 * @method Iban[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IbanRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Iban::class);
    }


    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'Iban';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_iban';


    /**
     * Get currencies by filters
     * @param array filters
     * @return array
     */
    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $bankName = MyTools::getOption($filters, 'bankName');
        $iban = MyTools::getOption($filters, 'iban');
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');
        $company = MyTools::getOption($filters, 'company');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'iban' => 'a.iban',
            'bankName' => 'a.bank_name',
            'company' => 'c.code',
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

        if (!empty($bankName)) {
            $where[] = ' a.bank_name ILIKE :bankName ';
            $parameters[':bankName'] = '%' . $bankName . '%';
        }


        if (!empty($company)) {
            $where[] = '  a.company_id = :company ';
            $parameters[':company'] = $company;
        }

        if (!empty($iban)) {
            $where[] = ' a.iban ILIKE :iban ';
            $parameters[':iban'] = '%' . $iban . '%';
        }

        if (!empty($search)) {
            $where[] = ' ( a.bank_name ILIKE :search OR a.iban ILIKE :search  )';
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











}
