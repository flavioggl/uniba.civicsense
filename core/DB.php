<?php

namespace Core;

class DB {

    const DATE_FORMAT = "Y-m-d H:i:s";

    private static $db;

    private function __construct() {
        self::$db = new \PDO(
            "mysql:host=localhost;dbname=civicsense",
            "root",
            "123456"
        );

        self::$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    /**
     * @return \PDO
     */
    static function db() {
        if (!(self::$db instanceof \PDO)) {
            $instance = new self();
        }
        return self::$db;
    }

    /**
     * @param $query string
     * @param array $params
     * @return bool|\PDOStatement
     */
    static function prepareExecute($query, array $params = []) {
        if (!$stmt = self::db()->prepare($query)) {
            logLn('Query syntax error on DB::prepareExecute');
            return redirect('/500');
        }
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * @param $table string
     * @param array $values
     * @return string last insert id
     */
    static function insert($table, array $values) {
        $params = [];
        foreach ($values as $column => $value) {
            $params["val_" . $column] = $value;
        }
        $query = "INSERT INTO `{$table}`(`" . implode("`,`", array_keys($values))
            . "`) VALUES(:" . implode(",:", array_keys($params)) . ")";
        self::prepareExecute($query, $params);
        return self::$db->lastInsertId();
    }

}