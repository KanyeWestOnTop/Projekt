<?php

namespace App\Controller;

use App\Model\Progress;
use App\Model\Exercise;

class ProgressController extends DefaultController
{

    public function index()
    {
        $progresses = Progress::all();
        $this->render("progress-history.html.twig", [
            "progresses" => $progresses
        ]);
    }

    public function create()
    {
        $exercises = Exercise::all();
        $this->render("progress-form.html.twig", [
            "exercises" => $exercises
        ]);
    }

    public function store(array $data)
    {
        $progress = new Progress();
        $progress->setExerciseId($data['exercise_id']);
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        $progress->save();
        $this->redirect("/exercises");
    }
    public function update(int $id, array $data)
    {
        $progress = Progress::findById($id);
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        $progress->save();
        $this->redirect("/exercises");
    }
    public function edit(int $id)
    {
        $progress = Progress::findById($id);
        $this->render("progress-form.html.twig", [
            "progress" => $progress
        ]);
    }
    public function delete(int $id): void
    {
        $progress = Progress::findById($id);
        $progress->delete();
        $this->redirect("/exercises");
    }
    public function history(int $id)
    {
        $progresses = Progress::findByExerciseId($id);
        $this->render("progress-history.html.twig", [
            "progresses" => $progresses
        ]);
    }

}
