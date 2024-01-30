<?php

namespace App\Controller;

use App\Model\Progress;
use App\Model\Exercise;

class ProgressController extends DefaultController
{

    public function show(int $id)
    {
        $exercise = Exercise::findById($id);
        $progresses = Progress::findByExerciseId($id);

        if ($exercise->getUserId() !== $_SESSION['user']->getId()) {
            $this->redirect("/");
        } else {
            $this->render("progress-history.html.twig", [
                "exercise" => $exercise,
                "progresses" => $progresses
            ]);
        }
    }

    public function create()
    {
        $userId = $_SESSION['user']->getId();
        $exercises = Exercise::findByUserId($userId);

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
            "weight" => "required|numeric|max:100",
            "reps" => "required|numeric|max:100",
            "date" => "required|date",
            "exercise_id" => "required"
        ];

        $this->validate($data, $rules);

        $progress = new Progress();
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);

        $dateObject = \DateTime::createFromFormat('d.m.Y', $data['date']);
        $formattedDate = $dateObject->format('Y-m-d');

        $progress->setDate($formattedDate);
        if (isset($data['exercise_id'])) {
            $progress->setExerciseId($data['exercise_id']);
        } else {
            $exercise = $_SESSION['exercise'];
            $exerciseId = $exercise->getId();
            $progress->setExerciseId($exerciseId);
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


        if ($exercise->getUserId() !== $_SESSION['user']->getId()) {
            $this->redirect("/");
        } else {
            $this->render("progress-form.html.twig", [
                "progress" => $progress,
                "exercise" => $exercise
            ]);
        }
    }

    public function update(int $id, array $data)
    {
        $rules = [
            "weight" => "required|numeric|max:100",
            "reps" => "required|numeric|max:100",
            "date" => "required|date"
        ];

        $this->validate($data, $rules);

        $progress = Progress::findById($id);
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);

        $dateObject = \DateTime::createFromFormat('d.m.Y', $data['date']);
        $formattedDate = $dateObject->format('Y-m-d');

        $progress->setDate($formattedDate);
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
