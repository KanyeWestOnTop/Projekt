<?php

namespace App\Utils;

class Validator
{

    public function validate(array $values, array $validationRules): array
    {
        $response = [];

        foreach ($validationRules as $field => $rules) {
            foreach (explode("|", $rules) as $rule) {
                if ($rule === "required" && (!array_key_exists($field, $values) || empty(trim($values[$field])))) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field is required.";
                }
                if ($rule === "alphanumeric" && preg_match("/^[a-z0-9 .\+]+$/i", $values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be alphanumeric character.";
                }
                if ($rule === "numeric" && !is_numeric($values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be numeric.";
                }
                if ($rule === "date" && !preg_match("/^\d{2}.\d{2}.\d{4}$/", $values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be a date.";
                }
                if ($rule === "alpha" && !preg_match("/^[a-z .\+]+$/i", $values[$field])) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be alphabetic character.";
                }
                if ($rule === "email" && !filter_var($values[$field], FILTER_VALIDATE_EMAIL)) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be a valid email address.";
                }
                if ($rule === "min:8" && strlen($values[$field]) < 8) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be at least 8 characters in length.";
                }
                if ($rule === "max:100" && strlen($values[$field]) > 100) {
                    $this->checkArrayKey($response, $field);
                    $response[$field] .= "The $field field must be less than 100 characters in length.";
                }
            }
        }

        return $response;
    }

    private function checkArrayKey(array &$response, string $field): void
    {
        if (!array_key_exists($field, $response)) {
            $response[$field] = "";
        } else {
            $response[$field] .= "<br>";
        }
    }
}
