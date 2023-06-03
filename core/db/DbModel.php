<?php

namespace app\core\db;

use app\core\Model;
use app\core\App;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey(): string;

    public function save()
    {
        if (!$this->beforeSave()) {
            return false;
        }

        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = App::$app->db->prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        if (!$statement->execute()) {
            throw new \Exception("Failed to save record to the database.");
        }
        return true;
    }

    public function update()
    {
        if (!$this->beforeSave()) {
            return false;
        }

        $tableName = $this->tableName();
        $primaryKey = $this->primaryKey();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => "$attr = :$attr", $attributes);
        $params = array_filter($params, function ($param) {
            return !str_contains($param, 'created_at') && !str_contains($param, 'created_by');
        });
        $statement = App::$app->db->prepare("UPDATE $tableName SET " . implode(',', $params) . " WHERE $primaryKey = :id");
        foreach ($attributes as $attribute) {
            if (!str_contains($attribute, 'created_at') && !str_contains($attribute, 'created_by')) {
                $statement->bindValue(":$attribute", $this->{$attribute});
            }
        }
        $statement->bindValue(":$primaryKey", $this->{$primaryKey});
        if (!$statement->execute()) {
            throw new \Exception("Failed to update record to the database.");
        }
        return true;
    }

    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode(" AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $statement = App::$app->db->prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        $record = $statement->fetchObject(static::class);
        return $record !== false ? $record : null;
    }

    public function beforeSave()
    {
        return true;
    }

    public function delete()
    {
        $tableName = $this->tableName();
        $primaryKey = $this->primaryKey();
        $statement = App::$app->db->prepare("DELETE FROM $tableName WHERE $primaryKey = :id");
        $statement->bindValue(":$primaryKey", $this->{$primaryKey});
        if (!$statement->execute()) {
            throw new \Exception("Failed to delete record from the database.");
        }
        return true;
    }
}
