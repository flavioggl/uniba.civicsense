<?php

namespace App\Model;

class Institution extends AbstractModel {

    const FK = "institution_id";
    const FIELD_RULES = [
        "institution_name"                => ["required", "max" => 45],
        "institution_vat"                 => ["required", "max" => 15],
        "institution_service_description" => ["required", "max" => 64]
    ];

    /**
     * @return string
     */
    static function tableName() {
        return "institutions";
    }

    /**
     * @return Manager
     */
    function manager() {
        return Manager::get([self::FK => $this->id]);
    }

    /**
     * @return Team[]|Collection
     */
    function teams() {
        return Team::getMulti([self::FK => $this->id]);
    }

    /**
     * @return Team|null
     */
    function firstFreeTeam() {
        foreach ($this->teams() as $team) {
            if (!$team->issuesProcessing()->count()) {
                return $team;
            }
        }
        return NULL;
    }

    /**
     * @param string $type
     * @return Collection|Issue[]
     */
    function issues($type = NULL) {
        switch ($type) {
            case "WAITING":
                $query = Issue::waiting();
                break;
            case Issue::STATUS_PROCESSING:
                $query = Issue::processing();
                break;
            case "CLOSED":
                $query = Issue::closed();
                break;
            default:
                $query = Issue::select();
        }
        return new Collection($query->where(self::FK, $this->id), Issue::class);
    }

}