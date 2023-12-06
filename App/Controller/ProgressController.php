<?php

namespace App\Controller;

use App\Model\Progress;
use App\Model\Exercise;
use App\Model\User;

class ProgressController extends DefaultController {

    public function index()
    {
        $users = User::all();
        $exercise = Exercise::all();
        $progresses = Progress::all();
        $this->render("progress-history.html.twig", [
            "users" => $users,
            "exercise" => $exercise,
            "progresses" => $progresses
        ]);
    }

    public function create() {
        $this->render("progress-form.html.twig");
    }

    public function store(array $data) {
        $progress = new Progress();
        $progress->setWeight($data['weight']);
        $progress->setReps($data['reps']);
        $progress->setDate($data['date']);
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