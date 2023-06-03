<?php 

namespace app\core;

abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'email';
    public const RULE_MIN = 'min';
    public const RULE_MAX = 'max';
    public const RULE_MATCH = 'match';
    public const RULE_UNIQUE = 'unique';

    public array $errors = [];

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            } else {
                echo "Property {$key} does not exist in the model";
            }
        }
        return $this;
    }

    abstract public function rules(): array;

    public function labels(): array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute] ?? $attribute;
    }

    public function validate()
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                switch ($ruleName) {
                    case self::RULE_REQUIRED:
                        $this->validateRequired($attribute, $value);
                        break;
                    case self::RULE_EMAIL:
                        $this->validateEmail($attribute, $value);
                        break;
                    case self::RULE_MIN:
                        $this->validateMin($attribute, $value, $rule['min']);
                        break;
                    case self::RULE_MAX:
                        $this->validateMax($attribute, $value, $rule['max']);
                        break;
                    case self::RULE_MATCH:
                        $this->validateMatch($attribute, $value, $rule['match']);
                        break;
                    case self::RULE_UNIQUE:
                        $this->validateUnique($attribute, $value, $rule);
                        break;
                    default:
                        break;
                }
            }
        }
        return empty($this->errors);
    }

    protected function validateRequired($attribute, $value)
    {
        if (!$value) {
            $this->addError($attribute, self::RULE_REQUIRED);
        }
    }

    protected function validateEmail($attribute, $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($attribute, self::RULE_EMAIL);
        }
    }

    protected function validateMin($attribute, $value, $min)
    {
        if (strlen($value) < $min) {
            $this->addError($attribute, self::RULE_MIN, ['min' => $min]);
        }
    }

    protected function validateMax($attribute, $value, $max)
    {
        if (strlen($value) > $max) {
            $this->addError($attribute, self::RULE_MAX, ['max' => $max]);
        }
    }

    protected function validateMatch($attribute, $value, $matchAttribute)
    {
        if ($value !== $this->{$matchAttribute}) {
            $matchLabel = $this->getLabel($matchAttribute);
            $this->addError($attribute, self::RULE_MATCH, ['match' => $matchLabel]);
        }
    }

    protected function validateUnique($attribute, $value, $rule)
    {
        $className = $rule['class'];
        $uniqueAttr = $rule['attribute'] ?? $attribute;
        $tableName = $className::tableName();
        $statement = App::$app->db->prepare("SELECT * FROM $tableName WHERE $uniqueAttr = :attr");
        $statement->bindValue(":attr", $value);
        $statement->execute();
        $record = $statement->fetchObject();
        if ($record) {
            $this->addError($attribute, self::RULE_UNIQUE, ['field' => $this->getLabel($attribute)]);
        }
    }

    protected function addError($attribute, $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }

    public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be a valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists',
        ];
    }

    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }

    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}
