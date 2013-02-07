<?php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$base_mem = memory_get_peak_usage();
$start_time = microtime(true); // Start the timer

// App name/directory
$app_name = 'app';

// Set up constants for app
if (!defined('DS')) {
  define('DS', DIRECTORY_SEPARATOR);
}

define('ROOT_PATH'  , dirname(dirname(__FILE__)));
define('APP_PATH'   , ROOT_PATH . DS . $app_name);
define('PUBLIC_PATH', dirname(__FILE__));
define('TMP_PATH'   , ROOT_PATH . DS . 'tmp');
define('LIB_PATH'   , ROOT_PATH . DS . 'lib');
define('BASE_PATH'  , LIB_PATH . DS . 'Dframework');
define('VIEW_PATH'  , APP_PATH . DS . 'Views');
define('CONFIG_PATH', APP_PATH . DS . 'Config');
define('ASSETS_PATH', APP_PATH . DS . 'Assets');

// Composer autoloader
require ROOT_PATH . DS . 'vendor' . DS . 'autoload.php';

// Autoloader
spl_autoload_register(function ($class_name) {
  $paths = [
    'lib' => [
      BASE_PATH,
      BASE_PATH . DS . 'Utility',
      BASE_PATH . DS . 'Renderers',
      BASE_PATH . DS . 'ViewHelpers'
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

// PHP ActiveRecord
require LIB_PATH . DS . 'php-activerecord' . DS . 'ActiveRecord.php';

// create a log channel
$log = new Logger('sql');
$log->pushHandler(new StreamHandler(TMP_PATH . DS . 'sql.log', Logger::DEBUG));

ActiveRecord\Config::instance()->set_logging(true);
ActiveRecord\Config::instance()->set_logger($log);

// Load configuration
require APP_PATH . DS . 'config' . DS . 'application.php';
require APP_PATH . DS . 'config' . DS . 'connections.php';
require APP_PATH . DS . 'config' . DS . 'routes.php';

// Dispatch current request
Dispatcher::dispatch($_SERVER['REQUEST_URI']);

$end_time  = microtime(true);  // Stop the timer
$extra_mem = memory_get_peak_usage();

// figure out the totals
$total_time = $end_time - $start_time;
$total_mem  = $extra_mem - $base_mem;

function formatBytes($size, $precision = 2) {
  $base = log($size) / log(1024);
  $suffixes = array('', 'k', 'MB', 'GB', 'TB');
  return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

ChromePhp::log("Total Time: {$total_time}");
ChromePhp::log('Total Mem Above Basline: ' . formatBytes($total_mem));
