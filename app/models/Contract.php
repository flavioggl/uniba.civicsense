<?php
namespace App\Model;


class Contract extends AbstractModel {

    function city() {
        return City::get($this->city_id);
    }

    function institution() {
        return Institution::get($this->_properties[Institution::FK]);
    }

    function trouble() {
        return Trouble::get($this->trouble_id);
    }

    /**
     * @return string
     */
    static function tableName() {
        return "contracts";
    }

    protected static function primaryKeys() {
        return [Institution::FK, "trouble_id", "city_id"];
    }

    /**
     * @param City $city
     * @return Collection|Institution[]
     */
    static function institutionsOfCity(City $city) {
        return new Collection(
            self::select(["city_id" => $city->id])
                ->join(Institution::tableName(), "id", Institution::FK)
                ->columns([Institution::tableName() . ".*"])
                ->distinct()
            , Institution::class);
    }

    /**
     * @param Ticket $ticket
     * @return Institution|null
     */
    static function firstInstitutionFromTicket(Ticket $ticket) {
        foreach (Contract::institutionsOfCity($ticket->city())
                     ->where("trouble_id", $ticket->trouble_id) as $institution) {
            return $institution;
        }
        return NULL;
    }

}