<?php
class PostsController extends AppController {

  // Filters
  public function beforeFilter () {
    // var_dump('before');
  }
  public function afterFilter () {
    // var_dump('after');
  }

  // Restful Methods
  public function index () {
    return [
      'posts' => [
        ['id' => 1, 'title' => 'Post 1'],
        ['id' => 2, 'title' => 'Post 2'],
        ['id' => 3, 'title' => 'Post 3'],
        ['id' => 4, 'title' => 'Post 4'],
        ['id' => 5, 'title' => 'Post 5'],
      ]
    ];
  }
  public function show ($id) {

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

  }
}