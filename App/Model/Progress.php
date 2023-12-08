<?php

namespace App\Model;

use App\Gateway\ProgressGateway;
use App\Model\Exercise;
use App\Model\User;


class Progress {

    private int $id = 0;
    private int $reps = 0;
    private int $weight = 0;
    private string $date = "";
    private int $exercise_id = 0;
    private int $user_id = 0;

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function getExerciseId(): int {
        return $this->exercise_id;
    }
    public function setExerciseId(int $exercise_id) {
        $this->exercise_id = $exercise_id;
    }
    public function getUserId(): int {
        return $this->user_id;
    }
    public function setUserId(int $user_id) {
        $this->user_id = $user_id;
    }
    public function setReps(int $reps) {
        $this->reps = $reps;
    }
    public function getReps(): int {
        return $this->reps;
    }
    public function setWeight(int $weight) {
        $this->weight = $weight;
    }
    public function getWeight(): int {
        return $this->weight;
    }
    public function setDate(string $date) {
        $this->date = $date;
    }
    public function getDate(): string {
        return $this->date;
    }

    public function save(): void {
        $gateway = new ProgressGateway();
        if($this->id){
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
    }
    public function delete() {
        $gateway = new ProgressGateway();
        $gateway->delete($this->id);
    } 
    public static function all(): array {
        $gateway = new ProgressGateway();
        $progresses = [];
        $dbProgresses = $gateway->all();
        foreach ($dbProgresses as $dbProgress) {
            $progress = new Progress();
            $progress->id = $dbProgress["id"];
            $progress->setReps($dbProgress["reps"]);
            $progress->setWeight($dbProgress["weight"]);
            $progress->setDate($dbProgress["date"]);

            $progresses[] = $progress;
        }
        return $progresses;
    }
    public static function findById(int $id): ?Progress {
        $gateway = new ProgressGateway();

        $tmpProgress = $gateway->findById($id);

        $progress = null;
        
        if ($tmpProgress) {
            $progress = self::create($tmpProgress);
        }
        return $progress;
    }
    public function setExercise(array $exerciseIds, array $pivotFields = []): void
    {
        $gateway = new ProgressGateway();
        $gateway->saveRelation($this->id, $exerciseIds, "exercise", "n", "progress", $pivotFields);
    }
    public function getExercise()
    {
        $gateway = new ProgressGateway();
        $exercise = [];

        $dbExercises = $gateway->getRelation($this->id, "exercise", "n", "progress");

        foreach ($dbExercises as $dbExercise) {
            $exercise = new Exercise();
            $exercise->setId($dbExercise["id"]);
            $exercise->setReps($dbExercise["name"]);
            $exercises[] = $exercise;
        }

        return $exercise;
    }
    public function setUser (array $userIds, array $pivotFields = []): void {
        $gateway = new ProgressGateway();
        $gateway->saveRelation($this->id, $userIds, "user", "n", "progress", $pivotFields);
    }

    public function getUser () {
        $gateway = new ProgressGateway();
        $user = [];

        $dbUsers = $gateway->getRelation($this->id, "user", "n", "progress");

        foreach ($dbUsers as $dbUser) {
            $user = new User();
            $user->setId($dbUser["id"]);
            $user->setPrename($dbUser["prename"]);
            $user->setLastname($dbUser["lastname"]);
            $user->setEmail($dbUser["email"]);
            $users[] = $user;
        }

        return $user;
    }
    public static function findByExerciseId(int $exerciseId): array
    {
        $gateway = new ProgressGateway();
        $progresses = [];
        $dbProgresses = $gateway->findByFields([
            "exercise_id" => $exerciseId
        ]);
        foreach ($dbProgresses as $dbProgress) {
            $progress = self::create($dbProgress);
            $progresses[] = $progress;
        }
        return $progresses;
    }
    private static function create(array $tmpProgress): Progress {
        $progress = new Progress();
        $progress->id = $tmpProgress["id"];
        $progress->reps = ($tmpProgress["reps"]);
        $progress->weight = ($tmpProgress["weight"]);
        $progress->date = ($tmpProgress["date"]);
        $progress->exercise_id = ($tmpProgress["exercise_id"]);
        $progress->user_id = ($tmpProgress["user_id"]);
        return $progress;
    }
    
    private function getAttributesAsAssociativeArray(): array {
        return [
            "id" => $this->id,
            "reps" => $this->reps,
            "weight" => $this->weight,
            "date" => $this->date,
            "exercise_id" => $this->exercise_id,
            "user_id" => $this->user_id
        ];
    }
}