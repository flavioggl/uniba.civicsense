<?php

namespace App\Controller;

use App\Model\City;
use App\Model\Contract;
use App\Model\Institution;
use App\Model\Ticket;
use App\Model\Trouble;
use Core\Request;

class CityController extends AbstractController {

    static function getHome() {
        return self::view('city/home', [
            "title" => self::auth()->loggedRow->city_name
        ]);
    }

    static function getList() {
        return self::view('city/list', [
            "title"  => "Lista Città/Comuni",
            "cities" => City::getMulti()->orderBy('city_name')
        ]);
    }

    static function getCreate() {
        return self::view('city/create', [
            "title" => "Crea Città/Comune"
        ]);
    }

    static function postCreate() {
        if (!$cityValues = Request::filter(INPUT_POST, City::fieldRules())) {
            return self::errorServer();
        }
        City::create($cityValues);
        return redirect("/cities");
    }

    static function getUpdate($id) {
        if (!$city = City::get($id)) {
            return self::errorUnauthorized();
        }
        return self::view('city/create', [
            "title" => "Aggiorna Città/Comune",
            "city"  => $city
        ]);
    }

    static function postUpdate($id) {
        if (!$city = City::get($id)) {
            return self::errorUnauthorized();
        }
        if (!$cityValues = Request::filter(INPUT_POST, City::fieldRules())) {
            return self::errorServer();
        }
        $city->update($cityValues);
        return redirect("/cities");
    }

    static function delete($id) {
        if (!$city = City::get($id)) {
            return self::errorUnauthorized();
        }
        $city->delete();
        return redirect("/cities");
    }

    static function getContracts() {
        $cityId = self::auth()->loggedRow->id;
        return self::view('city/contracts', [
            "title"        => "Appalti",
            "modals"       => ["city/contract-modal"],
            "contracts"    => Contract::getMulti(["city_id" => $cityId]),
            "troubles"     => Trouble::getMulti(),
            "institutions" => Institution::getMulti()
        ]);
    }

    static function postContractCreate() {
        if (!$values = Request::filter(INPUT_POST, [
            "trouble_id"     => ["required", "int"],
            "institution_id" => ["required", "int"]
        ])) {
            return self::errorServer();
        }
        $values["city_id"] = self::auth()->loggedRow->id;
        Contract::insert($values);
        return redirect("/contracts");
    }

    static function postContractDelete() {
        if (!$values = Request::filter(INPUT_POST, [
            "trouble_id"     => ["required", "int"],
            "institution_id" => ["required", "int"]
        ])) {
            return self::errorServer();
        }
        $values["city_id"] = self::auth()->loggedRow->id;
        if (!$contract = Contract::get($values)) {
            return self::errorPageNotFound();
        }
        $contract->delete();
        return redirect("/contracts");
    }

    static function getTicketsRejected() {
        return self::view('ticket/list', [
            "title"   => "Segnalazioni Anomale o Rigettate",
            "tickets" => Ticket::rejected(self::auth()->loggedRow),
            "hidden"  => ["closing_datetime", "status", "team"]
        ]);

    }

}