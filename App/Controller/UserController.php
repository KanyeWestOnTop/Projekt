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
        $user = new User();
        $user->setPrename($data['prename']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->save();
        $this->redirect("/login");
    }

    public function info()
    {
        $user = User::findById($_SESSION['user']);
        $this->render("userprofile.html.twig", [
            "user" => $user
        ]);
    }

    public function edit(int $id)
    {
        $user = User::findById($id);

        $this->render("register.html.twig", [
            "user" => $user
        ]);
    }

    public function update(int $id, array $data)
    {
        $user = User::findById($id);

        $user->setPrename($data['prename']);
        $user->setLastname($data['lastname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->save();

        $this->redirect("/");
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
}
