<?php
class SessionsController extends AppController {
  // Non-restful Methods
  public function login () {
    return [
      'name' => 'Andrew',
      'age' => 27
    ];
  }
  public function logout () {

  }
}