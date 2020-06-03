<?php

namespace App\Model;

use Core\Map;
use Core\Request;
use Faker\Factory;

class Ticket extends AbstractModel {

    const PRIORITY_GREEN = 'GREEN';
    const PRIORITY_YELLOW = 'YELLOW';
    const PRIORITY_RED = 'RED';
    const PHOTOS_DIR = "resources/img/tickets/";
    const VIDEOS_DIR = "resources/vid/tickets/";
    const DISTANCE_NEAR = 50;

    static $rules = [
        "trouble_id"  => ["required", "int"],
        //"city_cap"    => ["required", "max" => 6],
        "description" => ["required", "max" => 128],
        "priority"    => ["required", "values" => [self::PRIORITY_GREEN, self::PRIORITY_YELLOW, self::PRIORITY_RED]],
        "latitude"    => ["required", "float"],
        "longitude"   => ["required", "float"]
    ];

    /**
     * @return Trouble
     */
    function trouble() {
        return Trouble::get($this->trouble_id);
    }

    /**
     * @return Issue|null
     */
    function issue() {
        return $this->issue_id ? Issue::get($this->issue_id) : NULL;
    }

    /**
     * @return City|null
     */
    function city() {
        return $this->city_cap ? City::get(["cap" => $this->city_cap]) : NULL;
    }

    /**
     * @return Ticket|null
     */
    function searchDuplicate() {
        foreach (self::getMulti(["trouble_id" => $this->trouble_id, "city_cap" => $this->city_cap])
                     ->whereNot('code', $this->code)
                     ->whereNot('issue_id', NULL) as $ticket) {
            if (Map::distance($this->latitude, $this->longitude, $ticket->latitude, $ticket->longitude) <= self::DISTANCE_NEAR) {
                return $ticket;
            }
        }
        return NULL;
    }

    /**
     * @return string
     */
    static function tableName() {
        return "tickets";
    }

    protected static function primaryKeys() {
        return ["code"];
    }

    static function create(array $values) {
        $faker = Factory::create();
        $mapDetail = (new Map())->positionDetails($values["latitude"], $values["longitude"]);
        do {
            $values["code"] = strtoupper($faker->bothify('???###?#'));
        } while (self::get($values["code"]));
        if (!in_array($values["priority"], [self::PRIORITY_GREEN, self::PRIORITY_YELLOW, self::PRIORITY_RED])) {
            return FALSE;
        }
        $values["photo_src"] = "/" . self::PHOTOS_DIR . $values["code"] . ".jpg";
        $values["city_cap"] = $mapDetail ? $mapDetail["cap"] : NULL;
        if (!$ticket = self::insert($values)) {
            return FALSE;
        }
        if (!Request::upload("photo_src", __WEB_ROOT__ . $ticket->photo_src)) {
            $ticket->delete();
            return FALSE;
        }
        if (Request::upload("video_src", __WEB_ROOT__ . self::VIDEOS_DIR . $ticket->code . ".mp4")) {
            $ticket->video_src = "/" . self::VIDEOS_DIR . $ticket->code . ".mp4";
        }
        $ticket->save();
        if (!$duplicateTicket = $ticket->searchDuplicate()) {
            if ($ticket->city_cap) {
                Issue::createFromTicket($ticket);
            }
        } else {
            $duplicateTicket->issue()->bindTicket($ticket);
        }
        return $ticket;
    }

    static function rejected(City $city) {
        return self::getMulti(["city_cap" => $city->cap])
            ->where("issue_id", NULL)
            ->orderBy('creation_datetime');
    }

}