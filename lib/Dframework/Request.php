<?php
class Request {

  public $params;
  public $method;
  public $route;
  public $controller;
  public $action;

  public function __construct($options = []) {
    if (isset($options['params'])) {
      $this->params = $options['params'];
    }
    if (isset($options['method'])) {
      $this->method = $options['method'];
    }
    if (isset($options['route'])) {
      $this->route = $options['route'];
    }
  }
}