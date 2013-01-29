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

  public function beforeFilter () {}
  public function afterFilter () {}

  public function beforeRender () {}
  public function afterRender () {}

}