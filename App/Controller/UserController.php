<?php

namespace App\Controller;

use App\Model\User;


class UserController extends DefaultController
{

    public function index($id)
    {
        $user = User::findById($id);
        $this->render("userprofile.html.twig", [
            "user" => $user
        ]);
    }

    public function store(array $data)
{
    $rules = [
        "prename" => "required",
        "lastname" => "required",
        "email" => "required|email",
        "password" => "required|min:8"
    ];

    $this->validate($data, $rules);

    $user = new User();
    $user->setPrename($data['prename']);
    $user->setLastname($data['lastname']);
    $user->setEmail($data['email']);
    $user->setPassword($data['password']);
    $user->save();

    $this->redirect("/login");
}

public function changepassword ()
{
    $this->render("changepassword.html.twig");
}

public function updatepassword (array $data)
{
    $rules = [
        "password" => "required|min:8"
    ];

    $this->validate($data, $rules);

    $user = new User();
    $user->setPassword($data['password']);
    $user->save();

    $this->redirect("/profile");
}
    

    public function delete(int $id): void
    {
        $user = User::findById($id);
        $user->delete();
        $this->redirect("/");
    }

    public function create()
    {
        $this->render("register.html.twig");
    }

    public function info()
    {
        $user = User::findById($_SESSION['userId']);
        $this->render("userprofile.html.twig", 
        [
            "user" => $user
        ]);
    }
    
}
