<?php

namespace API;

class Autoloader {
  public function __construct() {
    set_include_path(SRC_PATH . PATH_SEPARATOR . get_include_path());
    spl_autoload_register(array($this, "autoload"));
  }

  public function autoload($classname) {
    $a = explode("\\", $classname);
    array_shift($a);
    $filename =  implode(DIRECTORY_SEPARATOR, $a) . ".php";
    if (file_exists(SRC_PATH . $filename)) {
      require_once($filename);
    }
  }
}
