<?php

/**
 * @param array $keys
 * @param array $array
 * @return bool
 */
function array_keys_exist(array $keys, array $array) {
    return !array_diff_key(array_fill_keys($keys, NULL), $array);
}

/**
 * @param $line string line to write
 * @param string $logFile log filename (without extesion .log)
 */
function logLn($line, $logFile = "exceptions") {
    error_log($line . PHP_EOL, 3, __DIR_LOGS__ . $logFile . ".log");
}

/**
 * @param string|callable $contentView Es: layout/master (string), or callable
 * @param array $params key => value
 */
function view($contentView, array $params = []) {
    $callable = !is_callable($contentView) ? include __DIR_VIEWS__ . $contentView . ".php" : $contentView;
    call_user_func($callable, (object) $params);
}

/**
 * @param string|callable $contentView Es: user/user (string), or callable
 * @param array $params key => value
 * @param string $masterLayout Under folder views/layouts. Default value is 'master'
 */
function viewWithMaster($contentView, array $params = [], $masterLayout = "master") {
    view("layout/" . $masterLayout, array_merge($params, ["contentView" => $contentView]));
}

function redirect($link) {
    header("Location: " . $link);
}

/**
 * @param DateTimeZone|string $tz
 * @return \Carbon\Carbon
 */
function now($tz = NULL) {
    return \Carbon\Carbon::now($tz);
}

/**
 * @param $value
 * @return \Carbon\Carbon|\Carbon\CarbonInterface
 */
function dateCarbon($value){
    return \Carbon\Carbon::create($value);
}