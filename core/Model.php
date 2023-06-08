<?php

namespace app\core;

use app\utils\Validation;

abstract class Model
{

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public array $errors = [];

    public function validate(): bool
    {
        $this->errors = [];

        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                $params = [];

                if (is_array($rule)) {
                    $ruleName = $rule[0];
                    $params = array_slice($rule, 1);
                }

                $validationMethod = [Validation::class, $ruleName];
                if (is_callable($validationMethod)) {
                    if (!call_user_func($validationMethod, $value, $params)) {
                        $this->addError($attribute, Validation::getErrorMessage($ruleName));
                    }
                } else {
                    throw new \Exception("Validation rule '{$ruleName}' is not a valid callback.");
                }
            }
        }

        return empty($this->errors);
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }
}
