<?php

namespace API\Service;

use \PDO;
use \API\Model\Content as MContent;

class Content {

  private $database;

  function __construct($database) {
    $this->database = $database;
  }

  function findAll(array $queryParams = []) {
    $contents = MContent::where("type", $queryParams["type"]);
    if ($queryParams["text"]) {
      $contents->where(function($query) use ($queryParams) {
        $query->where("title", "LIKE", "%$queryParams[text]%")
          ->orWhere("text", "LIKE", "%$queryParams[text]%");
      });
    }

    return [
      "status" => $contents->count(),
      "data" => $contents->get()->toArray(),
    ];
  }

  function find($id) {
    $content = MContent::find($id);
    return [
      "status" => $content->count(),
      "data" => $content->toArray(),
    ];
  }

  function insert($type, $data) {
    $content = new MContent();
    $content->id = md5(uniqid("content"));
    $content->type = $type;

    foreach ($data as $key => $value) {
      $content->{$key} = $value;
    }

    return [
      "status" => $content->save(),
      "data" => $content->toArray(),
    ];
  }

  function update($type, $id, $data) {

    $content = MContent::find($id);
    $content->type = $type;

    foreach ($data as $key => $value) {
      $content->{$key} = $value;
    }

    return [
      "status" => $content->save(),
      "data" => $content->toArray(),
    ];
  }

  function delete($id) {
    $content = MContent::find($id);
    return [
      "status" => $content->delete(),
    ];
  }
}