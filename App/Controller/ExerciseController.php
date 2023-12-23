<?php

namespace App\Controller;

use App\Model\Exercise;

class ExerciseController extends DefaultController
{

    public function index()
    {
        $exercises = Exercise::all();
        
        $this->render("exercises.html.twig", [
            "exercises" => $exercises,
        ]);
    }
    public function store(array $data)
    {
        $rules = [
            "name" => "required|alpha",
        ];
        

        $this->validate($data, $rules);

        $exercise = new Exercise();
        $exercise->setName($data['name']);
        $exercise->save();
        $this->redirect("/exercises");
    }
    public function create()
    {
        $this->render("exercises-form.html.twig");
    }
    public function update(int $id, array $data)
    {
        $rules = [
            "name" => "required|alpha",
        ];

        $this->validate($data, $rules);
        $exercise = Exercise::findById($id);
        $exercise->setName($data['name']);
        $exercise->save();

        $this->redirect("/exercises");
    }
    public function edit(int $id)
    {
        $exercise = Exercise::findById($id);

        $this->render("exercises-form.html.twig", [
            "exercise" => $exercise
        ]);
    }
    public function delete(int $id): void
    {
        $exercise = Exercise::findById($id);
        $exercise->delete();
        $this->redirect("/exercises");
    }

}
