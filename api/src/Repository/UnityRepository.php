<?php

namespace App\Repository;

use App\Entity\Unity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MsJwtBundle\Utils\MyTools;

/**
 * @method Unity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Unity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Unity[]    findAll()
 * @method Unity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnityRepository extends ServiceEntityRepository
{

    use \App\Traits\RepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unity::class);
    }

    /**
     * Entity name
     *
     * @var string
     */
    const ENTITY_NAME = 'unity';

    /**
     * Table name
     *
     * @var string
     */
    const TABLE_NAME = 'bl_unity';

    /**
     * Get currencies by filters
     * @param array filters
     * @return array
     */
    public function getByFilters($filters = [])
    {
        $search = MyTools::getOption($filters, 'search');
        $name = MyTools::getOption($filters, 'name');
        $page = MyTools::getOption($filters, 'index', 1);
        $maxPerPage = MyTools::getOption($filters, 'size', 10);
        $orderBy = MyTools::getOption($filters, 'sort_column', 'created_at');
        $order = MyTools::getOption($filters, 'sort_order', 'ASC');
        $company = MyTools::getOption($filters, 'company');

        $select = [
            'total' => 'count(*) OVER() ',
            'code' => 'a.code',
            'name' => 'a.name',
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

        if (!empty($name)) {
            $where[] = ' a.name ILIKE :name ';
            $parameters[':name'] = '%' . $name . '%';
        }

        if (!empty($company)) {
            $where[] = '  a.company_id = :company ';
            $parameters[':company'] = $company;
        }

        if (!empty($search)) {
            $where[] = ' ( a.name ILIKE :search )';
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
