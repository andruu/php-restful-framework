<?php
class PostsController extends AppController {

  // Dummy data
  private $posts = [
    ['id' => 1, 'title' => 'Something crazy just happened'],
    ['id' => 2, 'title' => 'Isreal loves Palestine'],
    ['id' => 3, 'title' => 'Toronto Raptors win the NBA Championship'],
    ['id' => 4, 'title' => 'I did it! I\'m the best mang!'],
    ['id' => 5, 'title' => 'Can\'t think of any clever titles'],
  ];

  // Filters
  public function beforeFilter () {
    parent::beforeFilter();
  }
  public function afterFilter () {
  }

  // Restful Methods
  public function index () {
    return [
      'posts' => $this->posts
    ];
  }

  public function show ($id) {
    return [
      'post' => $this->posts[($id - 1)]
    ];
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