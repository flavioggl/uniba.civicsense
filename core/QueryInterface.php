<?php

namespace Core;

interface QueryInterface {
    /**
     * @return int deleted rows
     */
    function delete();

    /**
     * @param array $columnValues column => value
     * @return int updated rows
     */
    function update(array $columnValues);

    /**
     * @param string $column default is *
     * @return int rows count
     */
    function count($column = "*");

    /**
     * @param array $columnNames
     */
    function columns(array $columnNames);

    /**
     * @param int $limit num of rows
     * @param int $offset offset
     */
    function limit($limit, $offset = NULL);

    /**
     * @param $orderBy string column name
     * @param string $direction asc or disc
     */
    function orderBy($orderBy, $direction = "ASC");

    /**
     * @param $column string column name
     * @param $expression mixed expression or value
     */
    function where($column, $expression);

    /**
     * @param $column string column name
     * @param $expression mixed expression or value
     */
    function whereNot($column, $expression);
}