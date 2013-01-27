<?php
class PagesController extends AppController {
  // Non-restful Methods
  public function show ($page) {
    var_dump($page);
  }
  public function test () {
    var_dump($this->request, $this->response);
  }
}