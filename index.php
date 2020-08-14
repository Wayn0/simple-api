<?php

print_r($_SERVER);
print("\n\n\n\n");
print_r(explode("/",$_SERVER['SERVER_SOFTWARE'])[0]);

// Log current time and mem usage
define('START_TIME', microtime(true));
define('START_MEMORY_USAGE', memory_get_usage());

// Constants
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)));
define('CONF_DIR', ROOT . DS . 'config' . DS);
define('LOG_DIR', ROOT . DS . 'logs' . DS);
define('LIB_DIR', ROOT . DS . 'lib' . DS);

// Autoload classes from the LIB dir
spl_autoload_register(function ($class_name) {
    if (file_exists(LIB_DIR . $class_name . '.class.php')) {
        require_once(LIB_DIR . $class_name . '.class.php');
    }
});

// Composer auto loading
if (file_exists(ROOT . DS . 'vendor'. DS .'autoload.php')) {
    require_once ROOT . DS . 'vendor'. DS .'autoload.php';
}

// Check that the app is installed and configured
if (!file_exists(CONF_DIR . 'config.inc.php')) {
    Util::errorResponseJSON("Server not properly configured.","500 Internal Server Error");
    exit;
}

// Load the config
require_once(CONF_DIR . 'config.inc.php');

// Configure the app for prod/dev
if (DEVELOPMENT_ENVIRONMENT == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    $log = new Log\Log(LOG_DIR, 5);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    $log = new Log\Log(LOG_DIR, LOG_LEVEL);
}


$request = new Request();

print_r($request->getPost('param1','integer'));

$log->logDebug(print_r($request,true));
$log->logDebug("Memory used by request: " . Util::bytesSize((memory_get_usage() - START_MEMORY_USAGE)) . " Time: " . round((microtime(true) - START_TIME),2) . "s");
