<?php

namespace App\Model;

use Core\Query;
use Core\QueryInterface;
use Traversable;

class Collection implements \IteratorAggregate, QueryInterface {

    /** @var Query */
    private $query;
    /** @var string class */
    private $class;

    function __construct(Query $query, $class) {
        $this->query = $query;
        $this->class = $class;
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator() {
        return new AbstractModelIterator($this->query, $this->class);
    }

    /**
     * @return array
     */
    function toArray() {
        $arr = [];
        foreach ($this as $model) {
            $arr[] = $model;
        }
        return $arr;
    }

    function getNext() {
        foreach ($this as $model) {
            return $model;
        }
        return NULL;
    }

    /*------------- QueryInterface METHODS ------------*/

    /**
     * @return Query
     */
    function getQuery() {
        return $this->query;
    }

    /**
     * @return int deleted rows
     */
    function delete() {
        return $this->query->delete();
    }

    /**
     * @param array $columnValues column => value
     * @return int updated rows
     * @throws \Exception
     */
    function update(array $columnValues) {
        return $this->query->update($columnValues);
    }

    /**
     * @param string $column default is *
     * @return int rows count
     */
    function count($column = "*") {
        return $this->query->count($column);
    }

    /**
     * @param array $columnNames
     * @return Collection
     */
    function columns(array $columnNames) {
        $this->query->columns($columnNames);
        return $this;
    }

    /**
     * @param int $limit num of rows
     * @param int $offset offset
     * @return Collection
     */
    function limit($limit, $offset = NULL) {
        $this->query->limit($limit, $offset);
        return $this;
    }

    /**
     * @param $orderBy string column name
     * @param string $direction asc or disc
     * @return Collection
     */
    function orderBy($orderBy, $direction = "ASC") {
        $this->query->orderBy($orderBy, $direction);
        return $this;
    }

    /**
     * @param $column string column name
     * @param $expression mixed expression or value
     * @return Collection
     */
    function where($column, $expression) {
        $this->query->where($column, $expression);
        return $this;
    }

    /**
     * @param $column string column name
     * @param $expression mixed expression or value
     * @return Collection
     */
    function whereNot($column, $expression) {
        $this->query->whereNot($column, $expression);
        return $this;
    }
}