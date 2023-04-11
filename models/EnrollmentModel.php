<?php

namespace app\models;

use app\core\db\DbModel;
use app\core\app;


class EnrollmentModel extends DbModel 
{
    public int $id = 0;
    public int $student_id = 0;
    public int $course_id = 0;
    public int $status = 0;
    public string $created_at = '';
    public string $updated_at = '';
    public $user_id;

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

    public static function findAllObjects(): array
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM enrollment";
        $statement = $db->pdo->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        // Set the created_at and updated_at fields
        $this->created_at = date('Y-m-d H:i:s');
        $this->updated_at = date('Y-m-d H:i:s');

        return true;
    }

    public function enroll($studentId)
    {
        $db = App::$app->db;
        $sql = "INSERT INTO enrollment (student_id, course_id) VALUES (:student_id, :course_id)";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':student_id', $studentId);
        $statement->bindValue(':course_id', $this->id);
        return $statement->execute();
    }

    

}