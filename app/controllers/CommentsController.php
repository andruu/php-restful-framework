<?php
class CommentsController extends AppController {

  // Filters
  public function beforeFilter () {
    $this->auto_render = false;
  }
  public function afterFilter () {
  }

  public function add () {
    var_dump(Router::routes());
    echo 'adding comment';
  }

  public function create () {

  }
  public function edit ($blog_id, $id) {
    var_dump($this->params);
    var_dump($blog_id, $id);
  }
  public function update ($id) {
    echo $id;
  }
  public function destroy ($id) {
    echo $id;
  }

}