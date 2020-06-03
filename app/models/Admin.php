<?php

namespace App\Model;


class Admin extends User {

    /**
     * @return string
     */
    static function tableName() {
        return "admins";
    }

}