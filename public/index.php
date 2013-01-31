<?php
// App name/directory
$app_name = 'app';

// Set up constants for app
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_PATH'  , dirname(dirname(__FILE__)));
define('APP_PATH'   , ROOT_PATH . DS . $app_name);
define('PUBLIC_PATH', dirname(__FILE__));
define('LIB_PATH'   , ROOT_PATH . DS . 'lib' . DS . 'Dframework');
define('VIEW_PATH'  , APP_PATH . DS . 'Views');
define('CONFIG_PATH', APP_PATH . DS . 'Config');

// Composer autoloader
require ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';

// Autoloader
spl_autoload_register(function ($class_name) {
  $paths = [
    'lib' => [
      LIB_PATH,
      LIB_PATH . DS . 'Utility',
      LIB_PATH . DS . 'Renderers',
      LIB_PATH . DS . 'ViewHelpers'
    ],
    'models' => [APP_PATH . DS . 'Models'],
    'controllers' => [APP_PATH . DS . 'Controllers'],
  ];
  foreach ($paths as $_paths) {
    foreach ($_paths as $path) {
      $file = $path . DS . $class_name . '.php';
      if (file_exists($file)) {
        require $file;
        return;
      }
    }
  }
});

// Load configuration
require APP_PATH . DS . 'config' . DS . 'application.php';
require APP_PATH . DS . 'config' . DS . 'connections.php';
require APP_PATH . DS . 'config' . DS . 'routes.php';

// Dispatch current request
Dispatcher::dispatch($_SERVER['REQUEST_URI']);

