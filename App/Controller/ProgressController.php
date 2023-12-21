<?php

namespace App\Controller;

use App\Model\Progress;
use App\Model\Exercise;
use App\Model\User;

class ProgressController extends DefaultController
{

    public function index()
    {
        $progresses = Progress::all();
        $this->render("progress-history.html.twig", [
            "progresses" => $progresses
        ]);
    }

    public function show(int $id)
    {
        $exercise = Exercise::findById($id);
        $progresses = Progress::findByExerciseId($id);
        $this->render("progress-history.html.twig", [
            "exercise" => $exercise,
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

    public function createthis(int $id)
    {
        $exercise = Exercise::findById($id);
        $_SESSION['exercise'] = $exercise->getId();
        $this->render("progress-form.html.twig", [
            "exercise" => $exercise
        ]);
    }

    public function store(array $data)
    {
        $rules = [
            "weight" => "required|numeric",
            "reps" => "required|numeric",
            "date" => "required|date",
            "exercise_id" => "required"
        ];

        $this->validate($data, $rules);

        $progress = new Progress();
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        if (isset($data['exercise_id'])) {
            $progress->setExerciseId($data['exercise_id']);
        } else {
            $exercise = $_SESSION['exercise'];
            $progress->setExerciseId($exercise);
        }
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']->getId();
            $progress->setUserId($userId);
        } 
        $exercise_id = $progress->getExerciseId();
        $progress->save();
        $this->redirect("/progresses/$exercise_id");
    }

    public function edit(int $id)
    {
        $exercise = Progress::findById($id)->getExercise();
        $progress = Progress::findById($id);
        $this->render("progress-form.html.twig", [
            "progress" => $progress,
            "exercise" => $exercise
        ]);
    }

    public function update(int $id, array $data)
    {
        $rules = [
            "weight" => "required|numeric",
            "reps" => "required|numeric",
            "date" => "required|date"
        ];

        $this->validate($data, $rules);

        $progress = Progress::findById($id);
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        $progress->save();
        $exercise_id = $progress->getExerciseId();
        $this->redirect("/progresses/$exercise_id");
    }

    public function delete(int $id): void
    {
        $progress = Progress::findById($id);
        $progress->delete();
        $exerciseId = $progress->getExerciseId();
        $this->redirect("/progresses/$exerciseId");
    }
}
