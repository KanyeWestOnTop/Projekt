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

        $exercise = Exercise::findById($id);
        $exercise->setName($data['name']);
        $exercise->save();

        $this->redirect("/exercises");
    }
    public function edit(int $id)
    {
        $exercises = Exercise::findById($id);

        $this->render("exercises-form.html.twig", [
            "exercises" => $exercises
        ]);
    }
    public function delete(int $id): void
    {
        $exercise = Exercise::findById($id);
        $exercise->delete();
        $this->redirect("/exercises");
    }

}