<?php
class Renderer {

  private static $engine;

  public static function engine ($engine = null, $formats = null) {
    if ($engine) {
      if (is_array($formats)) {
        foreach ($formats as $format) {
          self::$engine[$format] = $engine;
        }
      } else {
        self::$engine[$formats] = $engine;
      }
    } else if (!empty($engine)) {
      return self::$engine[$engine];
    } else {
      return self::$engine;
    }
  }

  public static function render ($controller, $view, $format, $layout, $locals = []) {
    $renderer = ucfirst(self::$engine[$format]) . 'Renderer';
    $view = $view . '.' . $format . '.' . self::$engine[$format];
    if (!empty($layout)) {
      $layout = $layout . '.' . $format . '.' . self::$engine[$format];
    }
    $renderer::render($controller, $view, $layout, $locals);
  }

  public static function staticFile ($file) {
    if (file_exists($file)) {
      return file_get_contents($file);
    } else {
      return false;
    }
  }

}