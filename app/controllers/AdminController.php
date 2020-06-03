<?php

namespace App\Controller;

use App\Model\Institution;
use App\Model\Trouble;
use Core\Request;

class AdminController extends AbstractController {

    static function getHome() {
        return self::view("admin/home", [
            "title" => "Amministrazione"
        ]);
    }

    static function getTroubles() {
        return self::view('trouble/list', [
            "title"    => "Tipologie di Segnalazione",
            "modals"   => ["trouble/modal"],
            "troubles" => Trouble::getMulti()
        ]);
    }

    static function getInstitutions() {
        return self::view("institution/list", [
            "title"        => "Azienda/Enti Gestori",
            "institutions" => Institution::getMulti()
        ]);
    }

    static function postTroubleCreate() {
        if (!($description = Request::post('description')) || (strlen($description) > 64)) {
            return self::errorServer();
        }
        Trouble::insert(["description" => $description]);
        return redirect("/troubles");
    }

    static function troubleDelete($id) {
        if ($trouble = Trouble::get($id)) {
            $trouble->delete();
        }
        return redirect("/troubles");
    }

}