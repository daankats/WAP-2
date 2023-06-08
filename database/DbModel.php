<?php

namespace app\database;

use app\core\Model;
use PDO;

abstract class DbModel extends Model
{
    protected static function getDb(): PDO
    {
        return Database::getInstance()->getPdo();
    }

    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;
    public function save()
    {
        $tableName = static::tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::getDb()->prepare(
            "INSERT INTO $tableName (" . implode(',', $attributes) . ")
            VALUES (" . implode(',', $params) . ")"
        );

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        return $statement->execute();
    }

    public function update()
    {
        $tableName = static::tableName();
        $primaryKey = $this->primaryKey();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => "$attr = :$attr", $attributes);
        $params = array_filter($params, function ($param) {
            return !str_contains($param, 'created_at') && !str_contains($param, 'created_by');
        });

        $statement = self::getDb()->prepare(
            "UPDATE $tableName SET " . implode(',', $params) . " WHERE $primaryKey = :id"
        );

        foreach ($attributes as $attribute) {
            if (!str_contains($attribute, 'created_at') && !str_contains($attribute, 'created_by')) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
        }

        $statement->bindValue(":id", $this->{$primaryKey});

        return $statement->execute();
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statement = self::getDb()->prepare("SELECT * FROM $tableName WHERE $sql");

        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class) ?: null;
    }

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

    public static function prepare($sql)
    {
        return self::getDb()->prepare($sql);
    }
}

