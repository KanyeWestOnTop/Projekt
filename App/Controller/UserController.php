<?php

namespace App\Controller;

use App\Model\user;

class UserController extends DefaultController
{

    public function index()
    {
        $users = User::all();

        $this->render("userprofile.html.twig", [
            "users" => $users
        ]);
    }

    public function login()
    {
        $this->render("login.html.twig");
    }

    public function register()
    {
        $this->render("register.html.twig");
    }
}
