<?php

namespace App\Controller;

use App\Model\User;

class LoginController extends DefaultController
{

    public function index()
    {
        $this->render("login.html.twig");
        
    }

    public function login(array $values) {
        $user = User::findByEmailAndPassword($values['email'], $values['password']);
        
        if ($user) {
            $_SESSION['user'] = $user;
        } else {
            $this->render("login.html.twig", [
                "error" => "Invalid email or password"
            ]);
        }
        $this->redirect("/");
    }
    

    public function logout()
    {
        session_destroy();
        $this->redirect("/login");
    }
}
