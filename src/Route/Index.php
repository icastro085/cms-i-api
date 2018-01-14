<?php

namespace API\Route;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Index {

  function __construct($container) {
  }

  function index(Request $request, Response $response) {
    return $response->withStatus(200)->getBody()->write("ok");
  }
}