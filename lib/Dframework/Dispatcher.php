<?php
class Dispatcher {

  private static $controller;
  private static $action;

  public static function parseParams ($method, $route, $path) {
    $params = [];
    $named_params = [];
    if ($route && !empty($route['params'])) {
      preg_match('|' . $route['path'] . '|', $path, $matches);
      foreach ($route['params'] as $key => $param) {
        $params[$param] = $matches[$key + 1];
        $named_params[$param] = $matches[$key + 1];
      }
    }
    foreach ($_GET as $key => $value) {
      $params[$key] = $value;
    }
    foreach ($_POST as $key => $value) {
      $params[$key] = $value;
    }
    if ($method === 'put' || $method === 'delete') {
      parse_str(file_get_contents("php://input"), $_params);
      $params = array_merge_recursive($params, $_params);
    }
    unset($params['url']);
    return [$params, $named_params];
  }

  public static function setControllerAndAction ($route) {
    $pairs = explode('#', $route['action']);

    self::$controller = ucfirst($pairs[0]) . 'Controller';
    self::$action     = $pairs[1];
  }

  public static function getController ($request, $response) {
    if (!class_exists(self::$controller)) {
      throw new Exception("Class '". self::$controller ."' does not exist.");
    } else {
      return new self::$controller($request, $response);
    }
  }

  public static function dispatch ($path) {
    $path   = explode('?', $path)[0];
    $method = strtolower($_SERVER['REQUEST_METHOD']);
    $route  = Router::find($method, $path);

    if (!$route) {
      throw new Exception("No route '{$path}' defined.");
    }

    list($params, $named_params) = self::parseParams($method, $route, $path);

    $request = new Request([
      'method' => $method,
      'route'  => $route,
      'params' => $params
    ]);
    $response   = new Response;

    // Check if closure is passed and run
    if (is_callable($route['action'])) {
      call_user_func($route['action'], $request, $response);

    // Else find controller
    } else {
      self::setControllerAndAction($route);
      $controller = self::getController($request, $response);
      call_user_func([$controller, 'invoke'], self::$action, $named_params);
    }

  }

}