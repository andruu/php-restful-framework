<?php
class PostsController extends AppController {

  // Filters
  public function beforeFilter () {
    var_dump('before');
  }
  public function afterFilter () {
    var_dump('after');
  }

  // Restful Methods
  public function index () {
    var_dump($this->request, $this->response);
  }
  public function show ($id) {
    var_dump($id);
    var_dump($this->params['id']);
  }
  public function add () {

  }
  public function create () {

  }
  public function edit () {

  }
  public function update () {

  }
  public function destroy () {

  }
  // Non-restful Methods
  public function search ($query, $page_number) {
    var_dump($query);
    var_dump($page_number);
    var_dump($this->request, $this->response);
  }
}