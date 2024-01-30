<?php

namespace App\Gateway;

use PDO;

class ProgressGateway extends BasicTableGateway {
        private PDO $connection;
        protected string $table = "progress";
        protected array $columns = [
            "id",
            "weight",
            "date",
            "exercise_id",
            "user_id"
        ];

        public function all(): array
    {
        $sql = $this->connection->prepare("SELECT * FROM $this->table ORDER BY date DESC");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>