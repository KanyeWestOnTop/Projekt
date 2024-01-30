<?php

namespace App\Controller;

use App\Model\User;
use App\Model\Progress;
use App\Model\Exercise;


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

    public function changepassword()
    {
        $this->render("changepassword.html.twig");
    }

    public function updatepassword(array $data)
    {
        $password = User::findById($_SESSION['user']->getId())->getPassword();

        $rules = [
            "oldpassword" => "required",
            "password" => "required|min:8"
        ];

        $this->validate($data, $rules);

        if (password_verify($data['oldpassword'], $password)) {
            $user = User::findById($_SESSION['user']->getId());
            $user->setPassword($data['password']);
            $user->save();

            $this->redirect("/profile");
        } else {
            return $this->render("changepassword.html.twig", [
                "passwordverif" => "Das alte Passwort ist falsch!"
            ]);
        }
    }

    public function deleteconfirm()
    {
        $this->render("deleteconfirm.html.twig");
    }

    public function changeemail()
    {
        $this->render("changeemail.html.twig");
    }

    public function updateemail(array $data)
    {
        $rules = [
            "email" => "required|email"
        ];

        $this->validate($data, $rules);

        $user = User::findById($_SESSION['user']->getId());
        $user->setEmail($data['email']);
        $user->save();

        $this->redirect("/profile");
    }

    public function delete(int $id): void
    {
        $user = User::findById($id);
        $user->delete();
        session_destroy();
        $this->redirect("/");
    }

    public function create()
    {
        $this->render("register.html.twig");
    }

    public function info()
{
    $user = User::findById($_SESSION['user']->getId());
    $exercises = Exercise::findByUserId($_SESSION['user']->getId());
    $topLifts = [];

    foreach ($exercises as $exercise) {
        $progressData = Progress::getExerciseWithMostWeight($exercise->getId());
        $topLifts[$exercise->getName()] = [
            'exercise' => $exercise,
            'exercise_id' => $exercise->getId(),
            'weight' => $progressData["weight"],
            'date' => $progressData["date"],
            'reps' => $progressData["reps"]
        ];
    }

    $this->render(
        "userprofile.html.twig",
        [
            "user" => $user,
            "topLifts" => $topLifts,
        ]
    );
}

    


}
