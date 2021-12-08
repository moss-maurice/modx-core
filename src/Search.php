<?php

namespace mmaurice\modx;

class Search extends \mmaurice\modx\Core
{
    public function getFullTableName($string)
    {
        return $this->modx()->getFullTableName($string);
    }

    public function escape($string)
    {
        return $this->db()->escape($string);
    }

    public function getInsertId()
    {
        return $this->db()->getInsertId();
    }

    public function query($sql)
    {
        return $this->db()->query($sql);
    }

    public function search($filter = [])
    {
        $query = $this->getRawSql($filter);

        $resource = $this->query($query);

        return $resource;
    }

    public function getList($filter = [])
    {
        $resource = $this->search($filter);

        if ($this->db()->getRecordCount($resource)) {
            $results = [];

            while ($row = $this->db()->getRow($resource)) {
                $results[] = $row;
            }

            return $results;
        }

        return null;
    }

    public function getItem($filter = [])
    {
        $resource = $this->search($filter);

        if ($this->db()->getRecordCount($resource)) {
            $item = $this->db()->getRow($resource);

            return $item;
        }

        return null;
    }

    public function getRawSql($filter = [])
    {
        if (!array_key_exists('alias', $filter)) {
            $filter['alias'] = '';
        }

        if (!array_key_exists('select', $filter)) {
            $filter['select'] = (!empty($filter['alias']) ? $filter['alias'] . '.*' : '*');
        }

        if (!is_array($filter['select'])) {
            $filter['select'] = [
                $filter['select'],
            ];
        }

        return "SELECT" . PHP_EOL
            . "\t" . implode("," . PHP_EOL . "\t", $filter['select']) . PHP_EOL
            . "FROM " . $filter['from'] . (!empty($filter['alias']) ? " " . $filter['alias'] : '') . PHP_EOL
            . (!empty($filter['join']) ? implode(PHP_EOL, $filter['join']) . PHP_EOL : "")
            . (!empty($filter['where']) ? "WHERE" . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $filter['where']) . PHP_EOL : "")
            . (!empty($filter['group']) ? "GROUP BY" . PHP_EOL . "\t" . implode("," . PHP_EOL . "\t", $filter['group']) . PHP_EOL : "")
            . (!empty($filter['having']) ? "HAVING" . PHP_EOL . "\t" . implode(PHP_EOL . "\t", $filter['having']) . PHP_EOL : "")
            . (!empty($filter['order']) ? "ORDER BY" . PHP_EOL . "\t" . implode("," . PHP_EOL . "\t", $filter['order']) . PHP_EOL : "")
            . (!empty($filter['limit']) ? "LIMIT " . intval($filter['limit']) . PHP_EOL : "")
            . (!empty($filter['offset']) ? "OFFSET " . intval($filter['offset']) . PHP_EOL : "");
    }
}
