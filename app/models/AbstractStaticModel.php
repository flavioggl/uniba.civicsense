<?php

namespace App\Model;

use Core\Query;

abstract class AbstractStaticModel {
    /**
     * @return string
     */
    abstract static function tableName();

    /**
     * @return array default is 'id'
     */
    protected static function primaryKeys() {
        return ["id"];
    }

    /**
     * @param array $conditions column => value
     * @return Query query object
     */
    static function select(array $conditions = []) {
        return Query::table(static::tableName())->whereMulti($conditions);
    }

    /**
     * @param $conditions int|string|array int|string if table has single PK
     * @param array $columns empty value exports all columns
     * @return bool|\stdClass
     */
    static function row($conditions, array $columns = []) {
        if (is_array($conditions)) {
            $query = static::select($conditions);
        } elseif (count(static::primaryKeys()) === 1) {
            $query = static::select(array_combine(static::primaryKeys(), [$conditions]));
        } else {
            return FALSE;
        }
        return $query->columns($columns)->execute()->fetch();
    }

    static function updateWhere(array $searchValues, array $values) {
        return Query::table(static::tableName())->whereMulti($searchValues)->update($values);
    }

    static function deleteWhere(array $searchValues) {
        return Query::table(static::tableName())->whereMulti($searchValues)->delete();
    }
}