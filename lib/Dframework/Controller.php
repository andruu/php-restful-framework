<?php
class Controller {

  protected $request;
  protected $response;
  protected $params;
  protected $layout;
  protected $auto_render = true;

  public function __construct ($request, $response) {
    $this->request = $request;
    $this->response = $response;
    $this->params = $request->params;
  }

  public function invoke ($action, $named_params = []) {
    $this->beforeFilter();
    $locals = call_user_func_array([$this, $action], $named_params);
    $this->afterFilter();
    if ($this->auto_render) {
      $this->render($locals);
    }
  }

  protected function render ($locals) {
    if ($this->response->preferred() == 'html' && empty($this->layout)) {
      $this->layout = 'application';
    }
    $this->beforeRender();
    if (empty($locals)) {
      $locals = [];
    }
    $locals = array_merge($locals, ['params' => $this->request->params]);
    $this->response->render($this->request->controller, $this->request->action, $this->layout, $locals);
    $this->afterRender();
  }

  protected function responds () {

  }

  public function __call ($name, $args) {
    if (!in_array($name, ['beforeFilter', 'afterFilter', 'beforeRender', 'afterFilter'])) {
      return false;
    }
    if (isset($args[0]) && is_callable($args[0])) {
      if (isset($args[1])) {
        if (isset($args[1]['only'])) {
          if (is_array($args[1]['only'])) {
            if (in_array($this->request->action, $args[1]['only'])) {
              call_user_func($args[0]);
            }
          } else {
            if ($this->request->action == $args[1]['only']) {
              call_user_func($args[0]);
            }
          }
        }
        if (isset($args[1]['except'])) {
          if (is_array($args[1]['except'])) {
            if (!in_array($this->request->action, $args[1]['except'])) {
              call_user_func($args[0]);
            }
          } else {
            if ($this->request->action != $args[1]['except']) {
              call_user_func($args[0]);
            }
          }
        }
      }
    }
  }

}

















