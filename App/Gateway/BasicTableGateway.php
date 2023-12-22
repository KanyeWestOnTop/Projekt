<?php

namespace App\Gateway;
use PDO;
use PDOStatement;

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
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): array|false {
        $sql = $this->connection->prepare("SELECT * FROM $this->table WHERE $this->primary = $id"); 
        return $sql->fetch(PDO::FETCH_ASSOC);
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

    public function insert(array $data): int {
      
    
        $columns = implode(",", array_keys($data));
 
        $placeholders = str_repeat("?, ", count($data) - 1) . "?";
        $values = array_values($data);


        $sql = "INSERT INTO $this->table($columns) VALUES ($placeholders)";
     
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);

    
        return $this->connection->lastInsertId();
    }

    public function update(int $id, array $data): void { 

        $params = [];
        $columns = "";

        foreach( $data as $key => $value ) {
            $columns .= "$key = ?,";
            $values[] = $value;
        }

        $columns = rtrim($columns, ",");

        $sql = "UPDATE $this->table SET $columns WHERE $this->primary = $id";
        $stmt = $this->connection->prepare($sql);

        $stmt->execute($values);

    }

    public function delete(int $id) {
        $sql = $this->connection->prepare("DELETE FROM $this->table WHERE $this->primary = $id");
        $sql->execute();
    }

    public function getRelation(int $id, string $relationTable, string $type = null, string $intermediateTable = null): array {
        if( $type == "n" ) {
            $sql = "SELECT * FROM $relationTable AS p LEFT JOIN $intermediateTable AS i ON p.id = i.{$relationTable}_id WHERE i.{$this->table}_id = $id";
        } else {
            $sql = "SELECT * FROM $relationTable WHERE {$this->table}_id = $id";
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function saveRelation(int $objId, array $relationIds, string $relationTable, string $type = null, string $intermediateTable = null, array $pivotFields = []): void {
        

        if($type == "n") {
            $sql = "DELETE FROM $intermediateTable WHERE {$this->table}_id = $objId";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            foreach($relationIds as $relationId) {


                $fields = "{$this->table}_id, {$relationTable}_id";
                $values = "$objId, $relationId";

                foreach($pivotFields as $key => $pivotField) {

                    $value = $pivotField[$relationId];
                    $value = $value[0];
                    
                    if(!$value) {
                        $value = "NULL";
                    }

                    $fields .= ", $key";
                    $values .= ", $value";
                }

                $sql = "INSERT INTO $intermediateTable ($fields) VALUES ($values)";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
            }
        } else {
            $sql = "UPDATE $relationTable SET {$this->table}_id = $relationIds[0]";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
        }

    }
}