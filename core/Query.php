<?php

namespace Core;

class Query implements QueryInterface {

    private $distinct = FALSE, $columns = [], $table, $joins = [], $where = [], $params = [], $orderBy = [], $limit, $offset;

    function __construct($tableName) {
        $this->table = self::sanitize($tableName);
    }

    /**
     * @param $tableName string
     * @return Query
     */
    static function table($tableName) {
        return new self($tableName);
    }

    /**
     * @return int deleted rows
     */
    function delete() {
        $query = "DELETE FROM " . $this->table . $this->whereQuery();
        return DB::prepareExecute($query, $this->params)->rowCount();
    }

    /**
     * @param array $columnValues
     * @return int
     * @throws \Exception
     */
    function update(array $columnValues) {
        $query = "UPDATE " . $this->table . " SET ";
        $sets = [];
        foreach ($columnValues as $column => $value) {
            if (is_array($value)) {
                throw new \Exception("Query->update() multi value is not supported");
            }
            $param = $this->setParams($column, $value, "setter_");
            $sets[] = self::sanitize($column) . " = " . (is_null($param) ? "NULL" : $param);
        }
        $query .= implode(", ", $sets) . $this->whereQuery();
        return DB::prepareExecute($query, $this->params)->rowCount();
    }

    /**
     * @return bool|\PDOStatement
     */
    function execute() {
        $query = "SELECT " . ($this->distinct ? "DISTINCT " : NULL)
            . ($this->columns ? implode(", ", $this->columns) : "*")
            . " FROM " . $this->table;
        if ($this->joins) {
            $query .= " " . implode(" ", $this->joins);
        }
        $query .= $this->whereQuery();
        if ($this->orderBy) {
            $query .= " ORDER BY " . implode(", ", $this->orderBy);
        }
        if (is_int($this->limit)) {
            $query .= " LIMIT " . $this->limit;
        }
        if (is_int($this->offset)) {
            $query .= " OFFSET " . $this->offset;
        }
        return DB::prepareExecute($query, $this->params);
    }

    function toArray() {
        if (count($this->columns) === 1) {
            return $this->execute()->fetchAll(\PDO::FETCH_COLUMN);
        }
        return $this->execute()->fetchAll();
    }

    /**
     * @param string $column default is *
     * @return int
     */
    function count($column = "*") {
        $this->columns = ["COUNT(" . ($this->distinct ? "DISTINCT " : NULL) . $column . ")"];
        return $this->execute()->fetchColumn();
    }

    function distinct() {
        $this->distinct = TRUE;
        return $this;
    }

    function columns(array $columnNames) {
        $this->columns = [];
        foreach ($columnNames as $columnName) {
            if (is_int(stripos($columnName, "*"))) {
                $this->columns[] = str_replace("`*`", "*", self::sanitize($columnName));
            } elseif (is_int(stripos($columnName, " AS "))) {
                $exploded = explode(" AS ", str_replace(" as ", " AS ", $columnName));
                foreach ($exploded as &$part) {
                    if (stripos("COUNT(", $part) !== FALSE) {
                        $part = self::sanitize($part);
                    }
                }
                $this->columns[] = implode(" AS ", $exploded);
            } else {
                $this->columns[] = self::sanitize($columnName);
            }
        }
        return $this;
    }

    function limit($limit, $offset = NULL) {
        if (is_int($limit)) {
            $this->limit = $limit;
        }
        if (is_int($offset)) {
            $this->offset = $offset;
        }
        return $this;
    }

    function orderBy($orderBy, $direction = "ASC") {
        $this->orderBy[] = self::sanitize($orderBy) . " " . $direction;
        return $this;
    }

    function join($newTable, $newColumn, $existingColumn, $existingTable = NULL) {
        return $this->joinQuery($newTable, $newColumn, $existingColumn, $existingTable);
    }

    function leftJoin($newTable, $newColumn, $existingColumn, $existingTable = NULL) {
        return $this->joinQuery($newTable, $newColumn, $existingColumn, $existingTable, "LEFT ");
    }

    function rightJoin($newTable, $newColumn, $existingColumn, $existingTable = NULL) {
        return $this->joinQuery($newTable, $newColumn, $existingColumn, $existingTable, "RIGHT ");
    }

    private function joinQuery($newTable, $newColumn, $existingColumn, $existingTable = NULL, $prefix = NULL) {
        if (is_null($existingTable)) {
            $existingTable = $this->table;
        }
        $newTable = self::sanitize($newTable);
        $this->joins[] = $prefix . "JOIN " . $newTable . " ON " . $newTable . "." . self::sanitize($newColumn)
            . " = " . self::sanitize($existingTable) . "." . self::sanitize($existingColumn);
        return $this;
    }

    function whereMulti(array $columnValue) {
        foreach ($columnValue as $column => $value) {
            $this->where($column, $value);
        }
        return $this;
    }

    function where($column, $expression) {
        if (!is_int(strpos($column, "."))) {
            $column = self::sanitize($this->table) . "." . $column;
        }
        $operator = self::getOperator($expression);
        $this->where[self::sanitize($column)][] = self::sanitize($column) . " " . $operator . " "
            . $this->setParams($column, $expression);
        return $this;
    }

    function whereNot($column, $expression) {
        if (!is_int(strpos($column, "."))) {
            $column = self::sanitize($this->table) . "." . $column;
        }
        $operator = self::getOperator($expression);
        $this->where[self::sanitize($column)][] = "NOT(" . self::sanitize($column) . " " . $operator . " "
            . $this->setParams($column, $expression) . ")";
        return $this;
    }

    private function whereQuery() {
        if (!$this->where) {
            return NULL;
        }
        $and = [];
        foreach ($this->where as $column => $expressions) {
            $and[] = "(" . implode(" OR ", $expressions) . ")";
        }
        return " WHERE " . implode(" AND ", $and);
    }

    /**
     * @param string $column
     * @param array|mixed $expression
     * @param string $prefix
     * @return string|null
     * @throws \Exception
     */
    private function setParams($column, $expression, $prefix = "where_") {
        if (is_null($expression)) {
            return NULL;
        }
        $column = str_replace(".", "_", $column);
        if (is_array($expression)) {
            $paramKeys = [];
            foreach ($expression as $value) {
                if (is_array($value)) {
                    throw new \Exception("Query->setParams() array inside array is not supported");
                }
                $paramKeys[] = $this->setParams($column, $value);
            }
            return "(" . implode(",", $paramKeys) . ")";
        }
        $baseParamKey = $prefix . self::desanitize($column);
        $i = 0;
        do {
            $paramKey = $baseParamKey . "_" . $i++;
        } while (array_key_exists($paramKey, $this->params));
        $this->params[$paramKey] = $expression;
        return ":" . $paramKey;
    }

    private static function getOperator(&$expression) {
        if (is_null($expression)) {
            return "IS NULL";
        }
        if (is_array($expression)) {
            return "IN";
        }
        $expression = trim($expression);
        foreach (["LIKE", "IN", "EXISTS", ">=", "<=", "<>", "<", ">"] as $operator) {
            if ((stripos($expression, $operator) === 0) && (strlen($operator) !== strlen($expression))) {
                $expression = trim(substr($expression, strlen($operator)));
                return $operator;
            }
        }
        return "=";
    }

    private static function sanitize($str) {
        if (is_int(strpos($str, "."))) {
            $strs = [];
            foreach (explode(".", $str) as $substr) {
                $strs[] = self::sanitize($substr);
            }
            return implode(".", $strs);
        }
        if (strpos($str, "`") !== 0) {
            $str = "`" . $str;
        }
        if (strrpos($str, "`") !== (strlen($str) - 1)) {
            $str .= "`";
        }
        return $str;
    }

    private static function desanitize($str) {
        return str_replace("`", NULL, $str);
    }
}