<?php
class PostsController extends AppController {

  // Filters
  public function beforeFilter () {
    parent::beforeFilter();
  }
  public function afterFilter () {
  }

  // Restful Methods
  public function index () {
    $posts = Post::find('all');
    return [
      'posts' => $posts
    ];
  }

  public function show ($id) {
    $post = Post::find_by_slug($id);
    return [
      'post' => $post,
      'previous' => $post->previous(),
      'next' => $post->next(),
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