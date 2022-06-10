<?php

namespace App\Traits;

/**
 *
 * @author walidsaadaoui
 */
trait RepositoryTrait
{

    /**
     * Return a ResultSetMapping
     *
     * @param array $columns
     * @return ResultSetMapping
     */
    public function initResultSetMapping(&$sql, $columns)
    {
        $rsm = new ResultSetMapping();

        foreach ($columns as $column => $value) {
            $sql .= $value . ' AS ' . $column . ', ';
            $rsm->addScalarResult($column, $column);
        }

        return $rsm;
    }

    public function query($table, $select, $conditions, $orders)
    {
        $rsm = new ResultSetMapping();
        $params = $filters = '';
        foreach ($select as $column => $value) {
            $params .= ($params ? ', ' : '') . $value . ' AS ' . $column;
            $rsm->addScalarResult($column, $column);
        }

        if ($conditions) {
            foreach ($conditions as $cond) {
                $filters .= $filters ? ' AND ' : ' WHERE ' . $cond;
            }
        }

        $order = ' ORDER BY ' . implode(', ', $orders);

        $sql = "SELECT $params FROM $table $filters $order";

//        $cacheKey = sha1($sql . $table . json_encode($select + $conditions + $orders));

        return $this->getEntityManager()
                        ->createNativeQuery($sql, $rsm)
                        // ->useResultCache(true, null, ['id' => null, 'tags' => ['staticList']])
                        ->getResult();
    }

}
