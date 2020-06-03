<?php

namespace App\Model;


class Manager extends User {

    /** @var Institution */
    var $institution;

    function __construct(array $properties) {
        parent::__construct($properties);
        $this->institution = Institution::get($properties[Institution::FK]);
    }

    /**
     * @return string
     */
    static function tableName() {
        return "managers";
    }

    static function fieldRules() {
        return array_merge(parent::fieldRules(), [
            "tax_code"      => ["required", "max" => 24],
            "birthday"      => ["required"],
            //Institution::FK => ["required", "int"]
        ]);
    }

}