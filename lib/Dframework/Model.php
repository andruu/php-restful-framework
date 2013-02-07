<?php
// Extending PHP ActiveRecord to work with twig and return Sets instead of arrays
class Model extends ActiveRecord\Model {

  // public function &__get ($name) {
  //     // check for getter
  //     if (method_exists($this, "get_$name")) {
  //         $name = "get_$name";
  //         $value = $this->$name();
  //         return $value;
  //     }
  //     $results = $this->read_attribute($name);
  //     if (is_array($results)) {
  //        $results = new Set($results);
  //     }
  //     return $results;
  // }

  // public function __call ($method, $args) {
  //   try {
  //     parent::__call($method, $args);
  //   } catch (Exception $e) {
  //     return false;
  //   }
  // }
  
  public function __isset ($name) {
      // check for getter
      if (method_exists($this, "get_$name")) {
          $name = "get_$name";
          $value = $this->$name();
          return $value;
      }
      $results = $this->read_attribute($name);
      return $results;
  }

  public static function find () {
    $results = call_user_func_array('parent::find', func_get_args());
    if (is_array($results)) {
      $results = new Set($results);
    }
    return $results;
  }

  public function previous () {
    return static::find('last', ['conditions' => ['id < ?', $this->id]]);
  }

  public function next () {
    return static::find('first', ['conditions' => ['id > ?', $this->id], 'order' => 'id ASC']);
  }

}