<?php
class TwigRenderer {
  public static function render ($controller, $view, $layout, $locals = []) {

    $view_path = VIEW_PATH . DS . ucwords($controller);
    $layout_path = VIEW_PATH . DS . 'Layouts';

    $loader = new Twig_Loader_Filesystem([$view_path, $layout_path]);
    $twig = new Twig_Environment($loader, ['debug' => true]);
    $twig->addExtension(new Twig_Extension_Debug());

    if (!empty($layout)) {
      $layout = $twig->loadTemplate($layout);
    } else {
      $layout = null;
    }

    return $twig->render($view, array_merge($locals, ['layout' => $layout]));
  }
}