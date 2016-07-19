<?php



error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_STRICT );
ini_set('display_errors', 0);
ini_set('display_startup_errors',0);

define('REQUEST_MICROTIME', microtime(true));
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));
define('ZF_CLASS_CACHE', 'data/cache/classes.php.cache'); if (file_exists(ZF_CLASS_CACHE)) require_once ZF_CLASS_CACHE;

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';
// require_once 'e_errorhandler.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();


