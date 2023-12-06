<?php

namespace App\Gateway;

class ProgressGateway extends BasicTableGateway {
    
        protected string $table = "progress";
        protected array $columns = [
            "id",
            "weight",
            "date",
            "exercise_id",
            "user_id"
        ];
}