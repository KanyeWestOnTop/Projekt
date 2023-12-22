<?php 

namespace App\Middleware;

class Authenticated
{
    public function handle()
    {
        $user = $_SESSION['user'];
        if (!$user) {
            if ($_SERVER["REQUEST_URI"] == "/login") {
                header("Location: /login");
                return exit();
            } else {
                header("Location: /register");
                return exit();
            }
            
        }
        return true;
    }
}