<?php
class Dispatcher {

  private static $controller;
  private static $action;
  private static $preferred;

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

  public static function preferred () {
    return self::$preferred;
  }

  public static function renderAsset ($file) {
    $asset_paths = [
      ASSETS_PATH . DS . 'Javascript',
      ASSETS_PATH . DS . 'Stylesheets',
      ASSETS_PATH . DS . 'Images',
    ];
    foreach ($asset_paths as $path) {
      if ($_file = Renderer::staticFile($path . DS . $file)) {
        return [$file, $_file];
      }
    }
  }

  public static function dispatch ($path) {
    $path   = explode('?', $path)[0];
    $method = strtolower($_SERVER['REQUEST_METHOD']);

    // Check if asset
    // @TODO: Refactor into asset manager with middleware
    if (preg_match('/\/assets\/[a-zA-z0-9-_\.]+/', $path)) {
      $_file = explode('/assets/', $path)[1];
      list($filename, $file) = self::renderAsset($_file);
      if (!$file) {
        http_response_code(404);
        throw new Exception("No route '{$path}' defined.");
      }
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      $response = new Response;
      $response->preferred($ext);
      $response->send($file);
      exit();
    }

    $route = Router::find($method, $path);

    if (!$route) {
      http_response_code(404);
      throw new Exception("No route '{$path}' defined.");
    }

    list($params, $named_params) = self::parseParams($method, $route, $path);

    $request = new Request([
      'method' => $method,
      'route'  => $route,
      'params' => $params,
      'path'   => $path
    ]);
    $response = new Response;
    $response->format = $request->format;
    $response->content_type = $request->content_type;

    // Check if closure is passed and run
    if (is_callable($route['action'])) {
      call_user_func($route['action'], $request, $response);

    // Else find controller
    } else {
      self::setControllerAndAction($route);
      $controller = self::getController($request, $response);
      call_user_func([$controller, 'invoke'], self::$action, $named_params);
    }

    self::$preferred = $response->preferred();
  }

}