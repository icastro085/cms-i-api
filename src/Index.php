<?php

namespace API;

use \PDO;
use \API\Route\Index as RIndex;
use \API\Route\Content as RContent;

class Index {
  function __construct($app) {
    $container = $app->getContainer();

    $this->setLogger($container);
    $this->setDatabase($container);

    $this->middleware($app);
    $this->route($app);

    $app->run();
  }

  private function setLogger($container) {
    $container['logger'] = function($c) {
      $logger = new \Monolog\Logger('api-logger');
      $file_handler = new \Monolog\Handler\StreamHandler(ROOT_PATH . 'logs/app.log');
      $logger->pushHandler($file_handler);
      return $logger;
    };
  }

  private function setDatabase($container) {
    $container['db'] = function ($c) {
      $db = $c['settings']['db'];
      $pdo = new PDO(
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'], $db['password']
      );
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      return $pdo;
    };

    return $container;
  }

  function middleware($app) {
    $app->add(new \Psr7Middlewares\Middleware\TrailingSlash(true));
    $app->add(new \API\Middleware);
  }

  function route($app) {
    //Index
    $app->get('/', RIndex::class . ':index');

    //Content
    $app->group('/content/{type}', function() {
      $this->get('/', RContent::class . ':index');
      $this->get('/{id}/', RContent::class . ':find');
      $this->post('/', RContent::class . ':create');
      $this->put('/{id}/', RContent::class . ':update');
      $this->delete('/{id}/', RContent::class . ':delete');
    });
  }
}