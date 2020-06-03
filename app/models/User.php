<?php

namespace App\Model;

class User extends AbstractModel {

    /**
     * @return string
     */
    static function tableName() {
        return "users";
    }

    private static function extendsUser() {
        return static::tableName() !== User::tableName();
    }

    function update(array $values) {
        if (self::extendsUser()) {
            parent::update(self::filterExtendedValues($values));
            return (new self($this->_properties))->update($values);
        }
        return parent::update(self::filterUserValues($values));
    }

    function save() {
        return $this->update($this->_properties);
    }

    function delete() {
        if (self::extendsUser()) {
            return parent::delete() and (new self($this->_properties))->delete();
        }
        return parent::delete();
    }

    static function fieldRules() {
        return [
            "email"    => ["required", "email", "max" => 64],
            "password" => ["required", "min" => 4],
            "name"     => ["required", "max" => 45],
            "surname"  => ["required", "max" => 45]
        ];
    }

    private static function secretKey($password) {
        return md5("#CIVIC#SENSE#" . $password);
    }

    private static function filterUserValues(array $values) {
        if (array_key_exists("password", $values)) {
            $values["password"] = self::secretKey($values["password"]);
        }
        return array_intersect_key($values, self::fieldRules() + ["id" => NULL]);
    }

    private static function filterExtendedValues(array $values) {
        return array_diff_key($values, self::fieldRules());
    }

    static function select(array $conditions = []) {
        $query = parent::select(self::extendsUser() ?
            self::filterExtendedValues($conditions) : self::filterUserValues($conditions));
        return self::extendsUser() ? $query->join(self::tableName(), "id", "id") : $query;
    }

    static function create(array $values) {
        if (!self::extendsUser()) {
            return self::insert(self::filterUserValues($values));
        }
        if (!$user = User::create($values)) {
            return FALSE;
        }
        return static::insert(self::filterExtendedValues(array_merge($user->_properties, $values)));
    }

}