<?php
namespace App\Model;

use App\Gateway\ExerciseGateway;

class Exercise {
    private int $id = 0;
    private string $name = "";

    public function getId(): int {
        return $this->id;
    }
    public function setId(int $id) {
        $this->id = $id;
    }
    public function setName(string $name) {
        $this->name = $name;
    }
    public function getName(): string {
        return $this->name;
    }
    public function save(): void {
        $gateway = new ExerciseGateway();
        if($this->id){
            $gateway->update($this->id, $this->getAttributesAsAssociativeArray());
        } else {
            $this->id = $gateway->insert($this->getAttributesAsAssociativeArray());
        }
    }
    public function delete() {
        $gateway = new ExerciseGateway();
        $gateway->delete($this->id);
    } 
    public static function all(): array {
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
    public static function findById(int $id): ?Exercise {
        $gateway = new ExerciseGateway();

        $tmpExercise = $gateway->findById($id);

        $exercise = null;
        
        if ($tmpExercise) {
            $exercise = self::create($tmpExercise);
        }
        return $exercise;
    }
    private static function create(array $tmpExercise): Exercise {
        $exercise = new Exercise();
        $exercise->id = $tmpExercise["id"];
        $exercise->name = ($tmpExercise["name"]);
        return $exercise;
    }

    private function getAttributesAsAssociativeArray(): array {
        return [
            "name" => $this->name
        ];
    }
}
?>