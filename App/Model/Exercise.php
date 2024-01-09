<?php

namespace App\Model;

use App\Gateway\ExerciseGateway;

class Exercise
{
    private int $id = 0;
    private int $user_id = 0;   
    private string $name = "";


    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function save(): void
    {
        $gateway = new ExerciseGateway();
        if ($this->id) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
    }
    public function delete()
    {
        $gateway = new ExerciseGateway();
        $gateway->delete($this->id);
    }
    
    public static function all(): array
    {
        $gateway = new ExerciseGateway();
        $exercises = [];
        $dbExercises = $gateway->all();
        foreach ($dbExercises as $dbExercise) {
            $exercise = new Exercise();
            $exercise->id = $dbExercise["id"];
            $exercise->setName($dbExercise["name"]);
            $exercises[] = $exercise;
        }
        return $exercises;
    }
    
    public static function findById(int $id): ?Exercise
    {
        $gateway = new ExerciseGateway();

        $tmpExercise = $gateway->findById($id);

        $exercise = null;

        if ($tmpExercise) {
            $exercise = self::create($tmpExercise);
        }
        return $exercise;
    }

    public static function findByUserId(int $user_id): array
    {
        $gateway = new ExerciseGateway();
        $exercises = [];
        $dbExercises = $gateway->findByUserId($user_id);
        foreach ($dbExercises as $dbExercise) {
            $exercise = new Exercise();
            $exercise->id = $dbExercise["id"];
            $exercise->setName($dbExercise["name"]);
            $exercises[] = $exercise;
        }
        return $exercises;
    }

    private static function create(array $tmpExercise): Exercise
    {
        $exercise = new Exercise();
        $exercise->id = $tmpExercise["id"];
        $exercise->user_id = $tmpExercise["user_id"];
        $exercise->name = ($tmpExercise["name"]);
        return $exercise;
    }

    private function getAttributesAsAssociativeArray(): array
    {
        return [
            "name" => $this->name,
            "user_id" => $this->user_id,
        ];
    }
}
