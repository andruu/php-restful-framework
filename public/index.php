<?php
// App name/directory
$app_name = 'app';

// Set up constants for app
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . DS . $app_name);
define('PUBLIC_PATH', dirname(__FILE__));
define('LIB_PATH', ROOT_PATH . DS . 'lib' . DS . 'Dframework');

// Autoloader
function __autoload ($class_name) {
  $paths = [
    'lib'         => [LIB_PATH],
    'models'      => [APP_PATH . DS . 'models'],
    'controllers' => [APP_PATH . DS . 'controllers'],
  ];
  foreach ($paths as $_paths) {
    foreach ($_paths as $path) {
      $file = $path . DS . $class_name . '.php';
      if (file_exists($file)) {
        include $file;
        return;
      }
    }
  }
}

// Load routes
include APP_PATH . DS . 'config' . DS . 'routes.php';

// Dispatch current request
Dispatcher::dispatch($_SERVER['REQUEST_URI']);

// Composer autoloader
include ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';
