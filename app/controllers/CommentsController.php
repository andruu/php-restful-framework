<?php
class CommentsController extends AppController {

  // Filters
  public function beforeFilter () {
    $this->auto_render = false;
  }
  public function afterFilter () {
  }

  public function add () {
    echo 'adding comment';
  }

  public function create () {

  }
  public function edit () {

  }
  public function update () {

  }
  public function destroy () {

  }

}