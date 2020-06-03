<?php

namespace App\Model;


class City extends User {

    static function tableName() {
        return "cities";
    }

    static function fieldRules() {
        return array_merge(parent::fieldRules(), [
            "city_name" => ["required", "max" => 45],
            "cap"       => ["required", "max" => 6]
        ]);
    }

}