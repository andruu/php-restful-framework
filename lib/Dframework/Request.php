<?php
class Request {

  public $params;
  public $method;
  public $route;
  public $path;
  public $controller;
  public $action;
  public $accepts;
  public $format;

  public function __construct($options = []) {
    if (isset($options['params'])) {
      $this->params = $options['params'];
    }
    if (isset($options['method'])) {
      $this->method = $options['method'];
    }
    if (isset($options['route'])) {
      $this->route = $options['route'];
      if (is_string($this->route['action'])) {
        list($this->controller, $this->action) = explode('#', $this->route['action']);
      }
    }
    if (isset($options['path'])) {
      $this->path = $options['path'];
    }
    $this->format = pathinfo($this->path, PATHINFO_EXTENSION);
    $this->accepts = array_map(function ($a) {
      if (preg_match("/\*\/\*/", $a)) {
        return false;
      } else {
        return trim($a);
      }
    }, explode(',', $_SERVER['HTTP_ACCEPT']));

    $this->content_type = $this->contentType();
  }

  public function contentType () {
    foreach (Response::mimeTypes() as $content_type => $mime_type) {
      if (is_array($mime_type)) {
        foreach ($mime_type as $_mime_type) {
          if (in_array($_mime_type, $this->accepts)) {
            return $content_type;
          }
        }
      } else {
        if (in_array($mime_type, $this->accepts)) {
          return $content_type;
        }
      }
    }
  }
}