<?php

namespace App\Controller;

use Core\Auth;

abstract class AbstractController {

    static function auth() {
        return Auth::Instance();
    }

    static function view($contentView, array $params = []) {
        return viewWithMaster($contentView, $params);
    }

    static function errorPageNotFound() {
        return redirect("/404");
    }

    static function errorUnauthorized() {
        return redirect("/401");
    }

    static function errorServer() {
        return redirect("/500");
    }

}