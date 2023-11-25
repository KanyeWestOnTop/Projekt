<?php
namespace App\Gateway;

class ExerciseGateway extends BasicTableGateway {

    protected string $table = "exercise";
    protected array $columns = [
        "id",
        "name"];
}





?>