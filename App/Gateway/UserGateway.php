<?php
namespace App\Gateway;

class UserGateway extends BasicTableGateway {

    protected string $table = "user";
    protected array $columns = [
        "id",
        "email",
        "password",
        "prename",
        "lastname"];
    
}
?> 