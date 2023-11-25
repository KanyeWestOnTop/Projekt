<?php

namespace App\Model;

use App\Gateway\ProgressGateway;
use App\Model\exercise;

class Progress
{

    private int $id = 0;
    private int $exercise_id = 0;
    private int $weight = 0;
    private int $reps = 0;
    private string $date = "";

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
    public function setExerciseId(int $exercise_id)
    {
        $this->exercise_id = $exercise_id;
    }
    public function getExerciseId(): int
    {
        return $this->exercise_id;
    }
    public function setWeight(int $weight)
    {
        $this->weight = $weight;
    }
    public function getWeight(): int
    {
        return $this->weight;
    }
    public function setReps(int $reps)
    {
        $this->reps = $reps;
    }
    public function getReps(): int
    {
        return $this->reps;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function setDate(string $date)
    {
        $this->date = $date;
    }

    public function save(): void
    {
        $gateway = new ProgressGateway();
        if ($this->id) {
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
    }
    public function delete()
    {
        $gateway = new ProgressGateway();
        $gateway->delete($this->id);
    }

    public static function all(): array
    {
        $gateway = new ProgressGateway();
        $progresses = [];
        $dbProgresses = $gateway->all();
        foreach ($dbProgresses as $dbProgress) {
            $progress = new Progress();
            $progress->id = $dbProgress["id"];
            $progress->setWeight($dbProgress["weight"]);
            $progress->setReps($dbProgress["reps"]);
            $progress->setDate($dbProgress["date"]);

            $progresses[] = $progress;
        }
        return $progresses;
    }

    public static function findById(int $id): ?Progress
    {
        $gateway = new ProgressGateway();
        $tmpProgress = $gateway->findById($id);
        $progress = null;
        if ($tmpProgress) {
            $progress = self::create($tmpProgress);
        }
        return $progress;
    }
    private static function create(array $tmpProgress): Progress
    {
        $progress = new Progress();
        $progress->id = $tmpProgress["id"];
        $progress->weight = $tmpProgress["weight"];
        $progress->reps = $tmpProgress["reps"];
        $progress->date = $tmpProgress["date"];

        if ($tmpProgress["exercise_id"]) {
            $progress->exercise_id = $tmpProgress["exercise_id"];
        }


        return $progress;
    }


    private function getAttributesAsAssociativeArray(): array
    {
        return [
            "exercise_id" => $this->exercise_id,
            "weight" => $this->weight,
            "reps" => $this->reps,
            "date" => $this->date
        ];
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

    public function getExercise(): ?Exercise
    {
        return Exercise::findById($this->exercise_id);
    }
}
