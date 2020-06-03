<?php

namespace App\Controller;

use App\Model\Institution;
use App\Model\Specialist;
use App\Model\Team;
use Core\Request;

class TeamController extends AbstractController {

    static function getList() {
        return self::view("team/list", [
            "title" => "Gruppi di risoluzione",
            "list"  => self::auth()->loggedRow->institution->teams()
        ]);
    }

    static function getCreate() {
        return self::view("team/create", ["title" => "Crea Gruppo di Risoluzione"]);
    }

    static function postCreate() {
        if (!$specialistValues = Request::filter(INPUT_POST, Team::fieldRules())) {
            return self::errorServer();
        }
        if (!$team = Team::insert([Institution::FK => self::auth()->loggedRow->institution->id])) {
            return self::errorServer();
        }
        self::insertSpecialists($team, $specialistValues);
        return redirect("/team/list");
    }

    static function addSpecialist() {
        if (!is_numeric($index = Request::post("index", FILTER_VALIDATE_INT))) {
            return self::errorServer();
        }
        return view('specialist/register', compact('index'));
    }

    static function getUpdate($id) {
        if (!$team = team::get($id)) {
            return self::errorPageNotFound();
        }
        return self::view("team/update", [
            "title"       => "Aggiorna Gruppo di Risoluzione",
            "specialists" => $team->specialists()
        ]);
    }

    static function postUpdate($id) {
        if (!$team = team::get($id)) {
            return self::errorPageNotFound();
        }
        $rules = array_merge(Team::fieldRules(), [
            "id" => ["array-int"]
        ]);
        if (!$specialistValues = Request::filter(INPUT_POST, $rules)) {
            return self::errorServer();
        }
        foreach ($team->specialists() as $specialist) {
            /** @var $specialist Specialist */
            if (!is_int($index = array_search($specialist->id, $specialistValues["id"]))) {
                $specialist->delete();
                continue;
            }
            self::updateSpecialist($specialist, $index, $specialistValues);
        }
        self::insertSpecialists($team, $specialistValues);
        return redirect("/team/list");
    }

    private static function insertSpecialists(Team $team, array $specialistValues) {
        foreach ($specialistValues["email"] as $index => $email) {
            Specialist::create([
                "email"    => $email,
                "password" => $specialistValues["password"][$index],
                "name"     => $specialistValues["name"][$index],
                "surname"  => $specialistValues["surname"][$index],
                "team_id"  => $team->id
            ]);
        }
    }

    private static function updateSpecialist(Specialist $specialist, $index, array & $specialistValues) {
        $specialist->update([
            "password" => $specialistValues["password"][$index],
            "name"     => $specialistValues["name"][$index],
            "surname"  => $specialistValues["surname"][$index]
        ]);
        foreach (array_keys($specialistValues) as $field) {
            unset($specialistValues[$field][$index]);
        }
    }

    static function delete($id) {
        if (!$team = Team::get($id)) {
            return self::errorUnauthorized();
        }
        if ($team->tickets()->count()) {
            return self::errorServer();
        }
        $team->specialists()->delete();
        $team->delete();
        return redirect("/team/list");
    }

}