<?php

namespace Core;


class Router {

    static $routes = [INPUT_GET => [], INPUT_POST => []];

    /**
     * @param $endpoint string endpoint
     * @param $requestType int INPUT_GET or INPUT_POST
     * @return bool|Route route if found
     */
    private static function deepSearch($endpoint, $requestType) {
        /** @var Route $route */
        foreach (self::$routes[$requestType] as $route) {
            if ($route->match($endpoint)) {
                return $route;
            }
        }
        return FALSE;
    }

    static function run() {
        $requestType = Request::type();
        $endpoint = Request::server("REDIRECT_URL");
        if (!array_key_exists($endpoint, self::$routes[$requestType])) {
            if (!$route = self::deepSearch($endpoint, $requestType)) {
                return redirect('/404');
            }
        } else {
            /** @var Route $route */
            $route = self::$routes[$requestType][$endpoint];
        }
        //check auth
        return $route->run();
    }

}