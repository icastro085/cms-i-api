<?php

namespace API\Route;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use \API\Service\Content as SContent;

class Content {

  private $service;

  function __construct($container) {
    $this->service = new SContent($container["db"]);
  }

  function index(Request $request, Response $response, array $args) {
    $queryParams = $request->getQueryParams();
    $queryParams["type"] = $args["type"];
    $result = $this->service->findAll($queryParams);
    if ($result["status"]) {
      return $response->withJson($result["data"], 200);
    }

    return $response->withJson([
      "status" => "Not Found",
    ], 404);
  }

  function find(Request $request, Response $response, array $args) {
    $id = $args["id"];
    $result = $this->service->find($id);
    if ($result["status"]) {
      return $response->withJson($result["data"], 200);
    }

    return $response->withJson([
      "status" => "Not Found",
    ], 404);
  }

  function create(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $result = $this->service->insert($args["type"], $data);

    if ($result["status"]) {
      return $response->withJson($result["data"], 201);
    }

    return $response->withJson([
      "status" => "Error",
    ], 400);
  }

  function update(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $result = $this->service->update($args["type"], $args["id"], $data);

    if ($result["status"]) {
      return $response->withJson($result["data"], 200);
    }

    return $response->withJson([
      "status" => "Error",
    ], 400);
  }

  function delete(Request $request, Response $response, array $args) {
    $id = $args["id"];
    $result = $this->service->delete($id);
    if ($result["status"]) {
      return $response->withJson([
        "status" => "Deleted"
      ], 200);
    }

    return $response->withJson([
      "status" => "Not Found",
    ], 404);
  }
}