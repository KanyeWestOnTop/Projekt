<?php

namespace App\Controller;

use App\Model\Progress;
use App\Model\Exercise;
use App\Model\User;

class ProgressController extends DefaultController {

    public function index() {
        $progresses = Progress::all();
        $this->render("progress-history.html.twig", [
            "progresses" => $progresses
        ]);
    }

    public function show(int $id){
        $exercise = Exercise::all();
        $progresses = Progress::findByExerciseId($id);
        $this->render("progress-history.html.twig", [
            "exercise" => $exercise,
            "progresses" => $progresses
        ]);
    }

    public function create() {
        $exercises = Exercise::all();
        $users = User::all();
        $this->render("progress-form.html.twig", [
            "exercises" => $exercises,
            "users" => $users
        ]);
    }

    public function store(array $data) {
        $progress = new Progress();
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        if (isset($data['exercise_id'])) {
            $progress->setExerciseId($data['exercise_id']);
        }
        if (isset($data['user_id'])) {
            $progress->setUserId($data['user_id']);
        }
        $progress->save();
        $this->redirect("/progress");
    }

    public function edit(int $id) {
        $progress = Progress::findById($id);
        $this->render("progress-form.html.twig", [
            "progress" => $progress
        ]);
    }

    public function update(int $id, array $data) {
        $progress = Progress::findById($id);
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
        $progress->save();
        $this->redirect("/progress");
    }

    public function delete(int $id): void {
        $progress = Progress::findById($id);
        $progress->delete();
        $this->redirect("/progress");
    }



}