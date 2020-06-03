<?php

namespace App\Model;


class Specialist extends User {

    /** @var Team */
    var $team;

    function __construct(array $properties) {
        parent::__construct($properties);
        $this->team = Team::get($properties["team_id"]);
    }

    /**
     * @return string
     */
    static function tableName() {
        return "specialists";
    }

    function __toString() {
        return $this->surname . " " . $this->name;
    }

}