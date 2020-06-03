<?php

namespace Core;

class Route {

    const ALLOWED_PARAM_PATTERNS = [
        "\{str\}" => "([^/]*)",
        "\{int\}" => "([0-9]+)"
    ];

    private $endpoint, $requestType, $callback, $authType;
    private $callbackArgs = [];

    /**
     * @param string $endpoint
     * @param int $requestType INPUT_GET or INPUT_POST
     * @param callable|string $callback callback function or Controller method string
     * @param string|array|bool $authType NULL (default) if can be authenticated or not, FALSE if must be
     *     unauthenticated, array of Auth types or Auth type
     * @throws \Exception
     */
    private function __construct($endpoint, $requestType, $callback, $authType = NULL) {
        $this->endpoint = strtr($endpoint, ["{" => "\{", "}" => "\}"]);
        $this->requestType = $requestType;
        if (is_string($callback)) {
            $callback = "\\App\\Controller\\" . $callback;
        } elseif (!is_callable($callback)) {
            throw new \Exception("Invalid Callable");
        }
        if ($authType && is_array($authType) && array_diff($authType, Auth::getTypes())) {
            throw new \Exception("Invalid Auth");
        } else if ($authType && is_string($authType) && !in_array($authType, Auth::getTypes())) {
            throw new \Exception("Invalid Auth");
        } else if (is_bool($authType) && ($authType !== FALSE)) {
            throw new \Exception("Invalid Auth");
        }
        $this->authType = $authType;
        $this->callback = $callback;
        Router::$routes[$requestType][$endpoint] = $this;
    }

    function match($endpoint) {
        $pattern = strtr('#^' . $this->endpoint . '$#', self::ALLOWED_PARAM_PATTERNS);
        if (preg_match($pattern, $endpoint, $matches)) {
            unset($matches[0]);
            $this->callbackArgs = array_values($matches);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @return mixed
     */
    function run() {
        $loggedType = Auth::Instance()->loggedType;
        if (is_string($this->authType) && ($loggedType !== $this->authType)) {
            return redirect("/401");
        } elseif (is_array($this->authType) && !in_array($loggedType, $this->authType)) {
            return redirect("/401");
        } elseif (($this->authType === FALSE) && $loggedType) {
            return redirect("/401");
        }
        return call_user_func_array($this->callback, $this->callbackArgs);
    }

    /**
     * @param string $endpoint endpoint
     * @param callable|string $callback function to call
     * @param null|string $auth auth
     * @return Route instance of get route
     * @throws \Exception
     */
    static function get($endpoint, $callback, $auth = NULL) {
        return new self($endpoint, INPUT_GET, $callback, $auth);
    }

    /**
     * @param string $endpoint endpoint
     * @param callable|string $callback function to call
     * @param null|string $auth auth
     * @return Route instance of post route
     * @throws \Exception
     */
    static function post($endpoint, $callback, $auth = NULL) {
        return new self($endpoint, INPUT_POST, $callback, $auth);
    }

    /**
     * @param string $endpoint
     * @param callable|string $callback
     * @param string $auth
     * @throws \Exception
     */
    static function both($endpoint, $callback, $auth = NULL) {
        $var = [
            new self($endpoint, INPUT_POST, $callback, $auth),
            new self($endpoint, INPUT_GET, $callback, $auth)
        ];
    }

}