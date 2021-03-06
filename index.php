<?php
function vx() {
  $args = func_get_args();
  echo "<pre>";
  foreach($args as $a) {
    var_dump($a);
  }
  echo "</pre>";
  exit;
}
//------------------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
//------------------------------------------------------------------------------
use \Symfony\Component\Yaml\Yaml;
//------------------------------------------------------------------------------
const DS = DIRECTORY_SEPARATOR;
const ROOT_PATH = __DIR__ . DS;
const SRC_PATH = ROOT_PATH . "src" . DS;
//------------------------------------------------------------------------------
require_once ROOT_PATH . "vendor/autoload.php";
require_once SRC_PATH . "Autoloader.php";
//------------------------------------------------------------------------------
new \API\Autoloader();
//------------------------------------------------------------------------------
$setting = file_get_contents(ROOT_PATH . "config/setting.yml");
$setting = Yaml::parse($setting);

$setting["logger"] = $setting["logger"] + [
  "level" => \Monolog\Logger::DEBUG,
  "path" => ROOT_PATH . "logs/app.log",
];

$config = [
  "settings" => $setting,
];
//------------------------------------------------------------------------------
//$connFactory = new \Illuminate\Database\Connectors\ConnectionFactory();
// $conn = $connFactory->make($setting["db"]);
// $resolver = new \Illuminate\Database\ConnectionResolver();
// $resolver->addConnection("default", $conn);
// $resolver->setDefaultConnection("default");
// \Illuminate\Database\Eloquent\Model::setConnectionResolver($resolver);
//------------------------------------------------------------------------------
$app = new \Slim\App($config);
new \API\Index($app);
//------------------------------------------------------------------------------