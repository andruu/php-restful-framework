<?php
class PhpRenderer {
  public static function render ($controller, $view, $layout, $locals = []) {
    $view_path = VIEW_PATH . DS . ucwords($controller);
    $layout_path = VIEW_PATH . DS . 'Layouts';
    extract($locals);
    ob_start();
    include $view_path . DS . $view;
    echo ob_get_clean();
  }
}