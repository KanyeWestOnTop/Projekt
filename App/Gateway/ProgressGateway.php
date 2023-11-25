<?php
namespace App\Gateway;

class ProgressGateway extends BasicTableGateway {

    protected string $table = "progress";
    protected array $columns = [
        "id",
        "exercise_id",
        "weight",
        "reps",
        "date"];
    
}





?> 