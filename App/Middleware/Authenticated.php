<?php 

namespace App\Middleware;

class Authenticated
{
    public function handle()
    {
        $user = $_SESSION['user'];
        if (!$user) {
            header("Location: /login");
            return exit();
        }
        return true;
    }
}