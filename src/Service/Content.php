<?php

namespace API\Service;

use \PDO;

class Content {

  static $instance;

  private $db;
  
  private $defaultParamsOr = [
    'title' => ['%[value]%', 'LIKE'],
    'text' => ['%[value]%', 'LIKE'],
  ];

  function __construct($db) {
    $this->db = $db;
  }

  static function getInstance() {
    if (!(self::$instance instanceof Content)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  function findAll(array $queryParams = []) {
    $sql = '
      SELECT * FROM content
      [where]
    ';

    $where = '';
    $whereParams = [];
    
    if (count($queryParams)) {
      $where = [];
      foreach($this->defaultParamsOr as $field => $value) {
        if ($queryParams[$field]) {
          $whereParams[":$field"] = str_replace(
            '[value]',
            $queryParams[$field],
            $value[0]
          );

          $where[] = "$field $value[1] :$field";
        }
      }
      $where = 'WHERE (' . join(' OR ', $where) . ')';
    }

    $sql = str_replace('[where]', $where, $sql);
    $stmt = $this->db->prepare($sql);
    $stmt->execute($whereParams);

    $result = $stmt->fetchAll();
    return [
      'status' => $stmt->columnCount(),
      'data' => $result,
    ];
  }

  function find($id) {
    $sql = '
      SELECT * FROM content
      WHERE id = :id
    ';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return [
      'status' => $stmt->columnCount(),
      'data' => $result,
    ];
  }

  function insert($type, $data) {
    $data['id'] = md5(uniqid('content'));
    $data['type'] = $type;
    $columns = array_keys($data);
    
    $sql = '
      INSERT INTO content
      (' . join(', ', $columns) . ')
      VALUES
      (:' . join(', :', $columns) .')
    ';

    $stmt = $this->db->prepare($sql);
    $stmt->execute($data);
    return [
      'status' => $stmt->rowCount(),
      'data' => $data
    ];
  }

  function update($type, $id, $data) {
    $data['type'] = $type;
    $columns = join(', ', array_map(
      function ($field) {
        echo 'sdfasdfa';
        return "$field = :$field";
      },
      array_keys($data)
    ));

    $sql = "
      UPDATE content
      SET $columns
      WHERE id = :id
    ";

    $data[':id'] = $id;
    $stmt = $this->db->prepare($sql);
    $stmt->execute($data);
    unset($data[':id']);

    return [
      'status' => $stmt->rowCount() >= 0,
      'data' => $data
    ];
  }

  function delete($id) {
    $sql = '
      DELETE FROM content
      WHERE id = :id
    ';

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':id' => $id,
    ]);

    return [
      'status' => $stmt->rowCount(),
    ];
  }
}