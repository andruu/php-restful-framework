<?php
class UsersController extends AppController {

  public $auto_render = false;

  public function beforeFilter () {
    parent::beforeFilter(function () {
      echo 'only';
    }, ['only' => ['index']]);
  }

  public function afterFilter () {
    parent::afterFilter(function () {
      echo 'except';
    }, ['except' => ['show']]);
  }

  // Restful Methods

  public function index () {
    echo ' index ';
  }

  public function add () {
    echo ' add action ';
  }

  public function create () {

  }

  public function show ($id) {
    echo "Show action id: " . $id;
  }
}