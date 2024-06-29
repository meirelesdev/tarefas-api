<?php

namespace App\Models;

use App\Handlers\ExceptionHandler;
use PDO;

class Task
{
  private $conn;
  private $table_name = "tasks";

  public $id;
  public $title;
  public $description;
  public $status;
  public $created_at;
  public $due_date;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function getById($id)
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
      $this->id = $row['id'];
      $this->title = $row['title'];
      $this->description = $row['description'];
      $this->status = $row['status'];
      $this->created_at = $row['created_at'];
      $this->due_date = $row['due_date'];
      return $row;
    } 
    return null;
  }

  public function list()
  {
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function create()
  {
    $query = "INSERT INTO " . $this->table_name . " SET title=:titulo, description=:descricao, status=:status, due_date=:data_prazo";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":titulo", $this->title);
    $stmt->bindParam(":descricao", $this->description);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":data_prazo", $this->due_date);

    return $stmt->execute();
  }

  public function update()
  {
    $query = "UPDATE " . $this->table_name . " SET title=:titulo, description=:descricao, status=:status, due_date=:data_prazo WHERE id=:id";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":titulo", $this->title);
    $stmt->bindParam(":descricao", $this->description);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":data_prazo", $this->due_date);
    $stmt->bindParam(":id", $this->id);

    return $stmt->execute();
  }

  public function delete()
  {
    $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(":id", $this->id);

    return $stmt->execute();
  }

  public function validateData($data)
  {
    $chavesEsperadas = ['title', 'description', 'due_date'];
    $erros = [];
    foreach ($chavesEsperadas as $chave) {
      if (!array_key_exists($chave, $data)) {
        $erros[] = "A chave '$chave' está faltando.";
      }
    }
    if (!empty($erros)) {
      throw new ExceptionHandler(json_encode($erros));
    }
  }
}