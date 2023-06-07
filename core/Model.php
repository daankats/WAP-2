<?php

namespace app\core;

use app\database\Database;
use app\utils\Validation;

abstract class Model
{

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public array $errors = [];

    public function validate()
    {
        $this->errors = [];

        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                if (is_callable($rule)) {
                    if (!$rule($value)) {
                        $this->addError($attribute, 'Custom validation rule failed.');
                    }
                } elseif (is_array($rule)) {
                    $ruleName = $rule[0];
                    $params = array_slice($rule, 1);

                    if (is_callable($ruleName)) {
                        if (!$ruleName($value, $params)) {
                            $this->addError($attribute, 'Custom validation rule failed.');
                        }
                    } elseif (method_exists(Validation::class, $ruleName)) {
                        $validationMethod = [Validation::class, $ruleName];
                        if (!call_user_func($validationMethod, $value, $params)) {
                            $this->addError($attribute, 'Validation rule failed.');
                        }
                    }
                } elseif (method_exists(Validation::class, $rule)) {
                    if (!call_user_func([Validation::class, $rule], $value)) {
                        $this->addError($attribute, 'Validation rule failed.');
                    }
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

