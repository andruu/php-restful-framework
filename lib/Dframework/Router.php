<?php
class Router {

  private static $routes   = [];
  private static $methods  = ['get', 'post', 'put', 'delete'];
  private static $defaults = [
    'index'   => ['get', '/:resource', ':resource#index'],
    'show'    => ['get', '/:resource/:id', ':resource#show'],
    'add'     => ['get', '/:resource/add', ':resource#add'],
    'create'  => ['post', '/:resource', ':resource#create'],
    'edit'    => ['get', '/:resource/edit/:id', ':resource#edit'],
    'update'  => ['put', '/:resource/:id', ':resource#update'],
    'destroy' => ['delete', '/:resource/:id', ':resource#destroy']
  ];

  public static function __callStatic ($name, $args) {
    if (!in_array($name, self::$methods)) {
      throw new Exception("No method '{$name}' defined.");
    } else {
      self::route($name, $args[0], $args[1]);
    }
  }

  public static function routes ($method = null) {
    if ($method) {
      return self::$routes[$method];
    } else {
      return self::$routes;
    }
  }

  public static function root ($action) {
    self::get('/', $action);
  }

  public static function find ($method, $path) {
    $route = null;
    if ($path === '/' && array_key_exists('/', self::routes($method))) {
      return self::routes($method)['/'];
    }
    unset(self::$routes[$method]['/']);
    foreach (self::routes($method) as $key => $_route) {
      if (preg_match('|^' . $key . '/?$|', $path)) {
        $route = $_route;
      }
    }
    return $route;
  }

  public static function resource ($resource, $options = []) {

    $routes = self::$defaults;

    if (!empty($options)) {
      // Rename resource url
      if (isset($options['as'])) {
        $_resource = $options['as'];
      } else {
        $_resource = $resource;
      }

      if (isset($options['controller'])) {
        $resource = $options['controller'];
      }

      // Set all methods except these
      if (isset($options['except'])) {
        foreach ($options['except'] as $action) {
          unset($routes[$action]);
        }
      }
      // Only set these methods
      if (isset($options['only'])) {
        $routes = [];
        foreach ($options['only'] as $action) {
          $routes[$action] = self::$defaults[$action];
        }
      }
    }

    // Setup default restful resource
    foreach ($routes as $route) {
      self::route($route[0], str_replace(':resource', $_resource, $route[1]), str_replace(':resource', $resource, $route[2]));
    }

    if (!empty($options)) {
      // Set non-restful methods
      foreach (self::$methods as $method) {
        if (isset($options[$method])) {
          foreach ($options[$method] as $action) {
            self::route($method, "/{$_resource}/{$action}", "{$resource}#" . explode('/', $action)[0]);
          }
        }
      }
    }
    // Nested resources
    if (!empty($options) && isset($options['resource'])) {
      if (isset($options['resource'][1]) && !is_array($options['resource'][1])) {
        $options['resource'][1] = [];
      }
      $options['resource'][1]['controller'] = $options['resource'][0];
      $path = $options['resource'][1]['path'] = $_resource . '/' . $options['resource'][0];
      self::resource($path, $options['resource'][1]);
    }
  }

  public static function route ($method, $path, $action) {
    $params = [];
    $offset = 0;
    while (preg_match("/:([a-zA-Z0-9_]+)/", substr($path, $offset), $matches, PREG_OFFSET_CAPTURE)) {
      $offset = $offset + $matches[0][1];
      $length = strlen($matches[0][0]);
      $offset = $offset + $length;
      $params[] = $matches[1][0];
    }
    if (!empty($params)) {
      $path = preg_replace("/:[a-zA-Z0-9_]+/", "([a-zA-Z0-9_-]+)", $path);
    }
    self::$routes[$method][$path] = ['path' => $path, 'action' => $action, 'params' => $params];
  }
}