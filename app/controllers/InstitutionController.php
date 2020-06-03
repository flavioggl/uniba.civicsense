<?php

namespace App\Controller;

use App\Model\Institution;
use App\Model\Manager;
use Core\Request;

class InstitutionController extends AbstractController {

    private static function postToColumns($institutionTempValues) {
        $institutionValues = [];
        foreach ($institutionTempValues as $col => $val) {
            $institutionValues[str_replace("institution_", NULL, $col)] = $val;
        }
        return $institutionValues;
    }

    static function getCreate() {
        return self::view("institution/create", ["title" => "Crea Azienda/Ente Gestore"]);
    }

    static function postCreate() {
        if (!($institutionTempValues = Request::filter(INPUT_POST, Institution::FIELD_RULES)) ||
            !($managerValues = Request::filter(INPUT_POST, Manager::fieldRules()))) {
            return self::errorPageNotFound();
        }
        if (!($institution = Institution::insert(self::postToColumns($institutionTempValues))) ||
            !($manager = Manager::create(array_merge([Institution::FK => $institution->id], $managerValues)))) {
            return self::errorServer();
        }
        return redirect("/");
    }

    static function getUpdate($id) {
        return self::view('institution/create', [
            "title"       => "Modifica Azienda/Ente Gestore",
            "institution" => Institution::get($id)
        ]);
    }

    static function postUpdate($id) {
        if (!$institution = Institution::get($id)) {
            return self::errorPageNotFound();
        }
        if (!$institutionTempValues = Request::filter(INPUT_POST, Institution::FIELD_RULES)) {
            return self::errorPageNotFound();
        }
        if (!$managerValues = Request::filter(INPUT_POST, Manager::fieldRules())) {
            return self::errorPageNotFound();
        }
        $institution->update(self::postToColumns($institutionTempValues));
        $institution->manager()->update($managerValues);
        return redirect("/");
    }

}