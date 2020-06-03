<?php

namespace Core;

use App\Model\Admin;
use App\Model\City;
use App\Model\Manager;
use App\Model\Specialist;
use App\Model\User;

class Auth {

    const TYPE_ADMIN = Admin::class;
    const TYPE_CITY = City::class;
    const TYPE_MANAGER = Manager::class;
    const TYPE_SPECIALIST = Specialist::class;

    static function getTypes() {
        return [self::TYPE_ADMIN, self::TYPE_MANAGER, self::TYPE_SPECIALIST, self::TYPE_CITY];
    }

    private static $instance;

    static function Instance() {
        return self::$instance;
    }

    /** @var null|Admin|Manager|Specialist|City user object */
    var $loggedRow;
    /** @var string class reference */
    var $loggedType;
    /** @var array tracking codes */
    var $trackingCodes;

    function __construct() {
        session_start();
        if (!empty($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
            $this->setUser($_SESSION['user_id']);
        }
        self::$instance = $this;
    }

    private function setUser($userId) {
        if ($this->loggedRow = Admin::get($userId)) {
            $this->loggedType = self::TYPE_ADMIN;
        } elseif ($this->loggedRow = City::get($userId)) {
            $this->loggedType = self::TYPE_CITY;
        } elseif ($this->loggedRow = Manager::get($userId)) {
            $this->loggedType = self::TYPE_MANAGER;
        } elseif ($this->loggedRow = Specialist::get($userId)) {
            $this->loggedType = self::TYPE_SPECIALIST;
        } else {
            return FALSE;
        }
        return TRUE;
    }

    function login(User $user) {
        if ($this->setUser($user->id)) {
            $_SESSION['user_id'] = $user->id;
        }
    }

    function logout() {
        $codes = isset($_SESSION['codes']) ? $_SESSION['codes'] : NULL;
        $this->loggedRow = $this->loggedType = NULL;
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['codes'] = $codes;
    }

    /**
     * @param $code string aggiungi codice di tracking
     */
    function addTrackingCode($code) {
        $_SESSION["codes"][] = $code;
    }

    /**
     * @return array codici di tracking
     */
    function getTrackingCodes() {
        return isset($_SESSION["codes"]) && is_array($_SESSION["codes"]) ?
            array_unique($_SESSION["codes"]) : [];
    }

    static function isManager() {
        return self::Instance()->loggedType === self::TYPE_MANAGER;
    }

    static function isSpecialist() {
        return self::Instance()->loggedType === self::TYPE_SPECIALIST;
    }

    static function isCity() {
        return self::Instance()->loggedType === self::TYPE_CITY;
    }

    static function isAdmin() {
        return self::Instance()->loggedType === self::TYPE_ADMIN;
    }

}