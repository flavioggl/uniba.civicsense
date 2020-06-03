<?php

namespace App\Controller;

use App\Model\User;
use Core\Auth;
use Core\Request;

class UserController extends AbstractController {

    static $rulesLogin = [
        "email"    => ["required", "email", "max" => 64],
        "password" => ["required", "min" => 5],
    ];

    static function getLogin() {
        return self::view("user/login", ["title" => "Login"]);
    }

    static function postLogin() {
        if (!self::auth()->loggedType) {
            if (!$request = Request::filter(INPUT_POST, self::$rulesLogin)) {
                return self::errorUnauthorized();
            }
            if (!$user = User::get(array_intersect_key($request, array_flip(["email", "password"])))) {
                return self::errorUnauthorized();
            }
            self::auth()->login($user);
        }
        return redirect("/");
    }

    static function logout() {
        Auth::Instance()->logout();
        redirect("/");
    }

}