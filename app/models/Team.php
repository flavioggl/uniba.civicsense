<?php

namespace App\Model;

class Team extends AbstractModel {

    /** @var Institution */
    var $institution;

    function __construct(array $properties) {
        parent::__construct($properties);
        $this->institution = Institution::get($properties[Institution::FK]);
    }

    function specialists() {
        return Specialist::getMulti(["team_id" => $this->id]);
    }

    /**
     * @return Collection|Issue[]
     */
    function issues() {
        return Issue::getMulti(["team_id" => $this->id]);
    }

    function issuesProcessing() {
        return $this->issues()->where('status', Issue::STATUS_PROCESSING);
    }

    function issuesClosed() {
        return $this->issues()->where("status", [Issue::STATUS_SOLVED, Issue::STATUS_FAILED]);
    }

    function issuesSolved() {
        return $this->issues()->where("status", Issue::STATUS_SOLVED);
    }

    function issuesFailed() {
        return $this->issues()->where("status", Issue::STATUS_FAILED);
    }

    /**
     * @return string
     */
    static function tableName() {
        return "teams";
    }

    static function fieldRules() {
        return [
            "email"    => ["required", "array-email", "max" => 64],
            "password" => ["required", "array", "min" => 5],
            "name"     => ["required", "array", "max" => 45],
            "surname"  => ["required", "array", "max" => 45]
        ];
    }

}