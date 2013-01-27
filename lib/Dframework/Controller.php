<?php
class Controller {

  protected $request;
  protected $response;
  protected $params;
  protected $auto_render = true;

  public function __construct ($request, $response) {
    $this->request = $request;
    $this->response = $response;
    $this->params = $request->params;
  }

  public function invoke ($action, $named_params = []) {
    $this->beforeFilter();
    call_user_func_array([$this, $action], $named_params);
    $this->afterFilter();
    $this->beforeRender();
    $this->render();
    $this->afterRender();
  }

  protected function render () {

  }

  public function beforeFilter () {}
  public function afterFilter () {}

  public function beforeRender () {}
  public function afterRender () {}

}