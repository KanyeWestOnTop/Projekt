<?php

namespace App\Gateway;

use PDO;

abstract class BasicTableGateway {

    private PDO $connection;
    protected string $table;
    protected array $columns;
    protected string $primary = "id";

    public function __construct() {
        $this->connection = new PDO("mysql:host=mysql;dbname=MyTraining", "root", "test05");
    }

    public function all(): array {
        $sql = $this->connection->prepare("SELECT * FROM $this->table");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById (int $id): array|false {
        $sql = $this->connection->prepare("SELECT * FROM $this->table WHERE $this->primary = $id");
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    
    }
    public function insert(array $data): int {
        $columns = implode(",", array_keys($data));
        $placeholder = str_repeat("?, ", count($data) -1) . "?";
        $values = array_values($data);
        
        $sql = "INSERT INTO $this->table($columns) VALUES($placeholder)";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
        
        return $this->connection->lastInsertId();
    }
   
    public function update(int $id, array $data): void {
        $values = [];
        $columns = "";

        foreach ( $data as $key=>$value) {
            $columns .= "$key = ?,";
            $values[] = $value;
        }
    

        $columns = rtrim($columns, ",");

        $sql = "UPDATE $this->table SET $columns WHERE $this->primary = $id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
    }
    public function delete(int $id): void {
        $sql = $this->connection->prepare("DELETE FROM $this->table WHERE $this->primary = $id");
        $sql->execute();
    }
    public function saveRelation(int $objId, array $relationIds, string $relationTable, string $type = null, string $intermediateTable = null): void {
        if ($type == "n") { 
            $sql = "DELETE FROM $intermediateTable WHERE {$this->table}_id = $objId";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            foreach($relationIds as $relationId) {
                $sql = "INSERT INTO $intermediateTable ({$this->table}_id, {$relationTable}_id) VALUES ($objId, $relationId)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
            }
        } else {
            $sql = "UPDATE $this->table SET {$relationTable}_id = $relationIds[0] WHERE id = $objId";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }

    }

    public function getRelation(int $objId, string $relationTable, string $type = null, string $intermediateTable = null): array {
        if ($type == "n") {
            $sql = "SELECT * FROM $relationTable AS p LEFT JOIN $intermediateTable AS i on p.id = i.{$relationTable}_id WHERE i.{$this->table}_id = $objId";
            $stmt = $this->connection->prepare($sql);

        } else {
            $sql = "SELECT * FROM $relationTable WHERE {$this->table}_id = $objId";
            $stmt = $this->connection->prepare($sql);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByUserId(int $user_id): array {
        $sql = "SELECT * FROM $this->table WHERE user_id = $user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByFields(array $fields): array|false {
        $sql = "SELECT * FROM $this->table WHERE ";
        $index = 0;
        foreach (array_keys($fields) as $key) {
            
            $sql .= "$key = ?";
            
            if ($index < count($fields) - 1) {
                $sql .= " AND ";    
            }
            $index++;
        }
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_values($fields));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}