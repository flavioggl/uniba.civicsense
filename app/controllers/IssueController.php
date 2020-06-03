<?php
namespace App\Controller;

use App\Model\Issue;
use Core\Map;
use Core\Request;
use Geocoder\Provider\GoogleMaps\GoogleMaps;

class IssueController extends AbstractController {

    static function postManage() {
        $institution = self::auth()->loggedRow->institution;
        if (!$values = Request::filter(INPUT_POST, [
            "id"      => ["required", "int", "values" => $institution->issues("WAITING")->columns(["id"])->getQuery()->toArray()],
            "team_id" => ["required", "int", "values" => $institution->teams()->columns(['id'])->getQuery()->toArray()]
        ])) {
            return self::errorUnauthorized();
        }
        Issue::get($values["id"])->update(["team_id" => $values["team_id"]]);
        return redirect('/issues/' . Issue::STATUS_PROCESSING);
    }

    static function getMap($id) {
        if (!$issue = Issue::get($id)) {
            return self::errorPageNotFound();
        }
        return self::view('issue/map', ["imgSrc" => Map::markersMap($issue->tickets())]);
    }

}