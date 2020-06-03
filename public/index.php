<?php

define("__DIR_ROOT__", dirname(__DIR__) . "/");
define("__WEB_ROOT__", dirname(__DIR__) . "/public/");
define("__DIR_VIEWS__", __DIR_ROOT__ . "app/views/");
define("__DIR_MODELS__", __DIR_ROOT__ . "app/models/");
define("__DIR_CONTROLLERS__", __DIR_ROOT__ . "app/controllers/");
define("__DIR_LOGS__", __DIR_ROOT__ . "logs/");

require_once __DIR_ROOT__ . "/vendor/autoload.php";
require_once __DIR_ROOT__ . "/app/functions.php";

$auth = new \Core\Auth();
date_default_timezone_set("Europe/Rome");
\Carbon\Carbon::setLocale('it');

try {
    include_once __DIR_ROOT__ . "/app/routes.php";
    \Core\Router::run();
} catch (\Exception $ex) {
    logLn(now()->toDateTimeString() . " " . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
    http_response_code(500);
    viewWithMaster('error/500');
}