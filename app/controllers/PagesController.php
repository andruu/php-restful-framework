<?php
class PagesController extends AppController {

  public $auto_render = false;

  // Non-restful Methods
  public function show ($page) {
    echo 'pages/:page';
    var_dump($page);
  }

  public function test () {
    echo 'pages/test';
    var_dump($this->request, $this->response);
  }
}