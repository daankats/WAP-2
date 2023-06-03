<?php

namespace app\models;

use app\core\db\DbModel;
use app\core\App;

class EnrollmentModel extends DbModel
{
    public int $id = 0;
    public int $student_id = 0;
    public int $course_id = 0;
    public int $status = 0;
    public string $created_at = '';
    public string $updated_at = '';
    public int $user_id = 0;


    public static function tableName(): string
    {
        return 'enrollment';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return ['student_id', 'course_id', 'status'];
    }

    public function labels(): array
    {
        return [
            'student_id' => 'Student ID',
            'course_id' => 'Course ID',
            'status' => 'Status'
        ];
    }

    public function rules(): array
    {
        return [
            'student_id' => [self::RULE_REQUIRED],
            'course_id' => [self::RULE_REQUIRED],
            'status' => [self::RULE_REQUIRED]
        ];
    }

    public function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        return true;
    }

    public static function findAllObjects(): array
    {
        $db = App::$app->db;
        $tableName = static::tableName();
        $sql = "SELECT * FROM $tableName";
        $statement = $db->prepare($sql);
        $statement->execute();
        $objects = [];
        while ($row = $statement->fetchObject(static::class)) {
            $objects[] = $row;
        }
        return $objects;
    }

    public static function findAll($id = null)
    {
        $sql = "SELECT * FROM " . self::tableName() . " WHERE student_id = :id";
        $stmt = App::$app->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
