<?php

namespace app\database;

use app\core\Model;
use PDO;

abstract class DbModel extends Model
{
    protected static function getDb(): PDO
    {
        return DbConnection::getConnection()->getPdo();
    }

    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;

    public static function prepare($sql)
    {
        return self::getDb()->prepare($sql);
    }

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

    public function delete()
    {
        $tableName = static::tableName();
        $primaryKey = $this->primaryKey();
        $statement = self::getDb()->prepare("DELETE FROM $tableName WHERE $primaryKey = :id");
        $statement->bindValue(":id", $this->{$primaryKey});
        return $statement->execute();
    }

    public static function findAllObjects(): array
    {
        $tableName = static::tableName();
        $db = self::getDb();
        $sql = "SELECT * FROM $tableName";
        $statement = $db->prepare($sql);
        $statement->execute();
        $objects = [];
        while ($row = $statement->fetchObject(static::class)) {
            $objects[] = $row;
        }
        return $objects;
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $conditionStr = implode(' AND ', array_map(fn ($attr) => "$attr = :$attr", array_keys($where)));
        $query = "SELECT * FROM $tableName WHERE $conditionStr";

        $db = self::getDb();
        $statement = $db->prepare($query);

        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }

        $statement->execute();
        $result = $statement->fetchObject(static::class);

        return ($result !== false) ? $result : null;
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


}
