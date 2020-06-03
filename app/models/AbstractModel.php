<?php

namespace App\Model;

use Core\DB;

abstract class AbstractModel extends AbstractStaticModel {

    protected $_properties = [];

    function __construct(array $properties) {
        $this->_properties = $properties;
    }

    function __get($name) {
        if (array_key_exists($name, $this->_properties)) {
            return $this->_properties[$name];
        }
        return NULL;
    }

    function __set($name, $value) {
        $this->_properties[$name] = $value;
    }

    function toArray() {
        return $this->_properties;
    }

    function save() {
        if (!array_keys_exist(static::primaryKeys(), $this->_properties)) {
            return FALSE;
        }
        $searchValues = array_intersect_key($this->_properties, array_fill_keys(static::primaryKeys(), NULL));
        $updateValues = array_diff_key($this->_properties, array_fill_keys(static::primaryKeys(), NULL));
        return static::updateWhere($searchValues, $updateValues) === 1;
    }

    function update(array $values) {
        $searchValues = array_intersect_key($this->_properties, array_fill_keys(static::primaryKeys(), NULL));
        if (static::updateWhere($searchValues, $values) !== 1) {
            return FALSE;
        }
        $this->_properties = array_merge($this->_properties, $values);
        return $this;
    }

    function delete() {
        $searchValues = array_intersect_key($this->_properties, array_fill_keys(static::primaryKeys(), NULL));
        return static::deleteWhere($searchValues) === 1;
    }

    /**
     * @param array|int|string $conditions
     * @return static model object with properties
     */
    static function get($conditions) {
        if (!$row = static::row($conditions)) {
            return NULL;
        }
        return new static((array) $row);
    }

    /**
     * @param array $conditions
     * @return Collection|static[]
     */
    static function getMulti(array $conditions = []) {
        return new Collection(static::select($conditions), static::class);
    }

    /**
     * @param array $values column => value
     * @return static
     */
    static function insert(array $values) {
        if (!$id = DB::insert(static::tableName(), $values)) {
            return static::get($values);
        }
        return static::get($id);
    }

}