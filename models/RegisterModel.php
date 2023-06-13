<?php

namespace app\models;

use app\database\DbModel;
use app\utils\Validation;


class RegisterModel extends DbModel 
{
    public int $id = 0;
    public int $student_id = 0;
    public int $exam_id = 0;
    public string $created_at = '';
    public $user_id;

    public function rules(): array
    {
        return [
            'student_id' => [Validation::RULE_REQUIRED],
            'exam_id' => [Validation::RULE_REQUIRED],
        ];
    }
    public static function tableName(): string
    {
        return 'registrations';
    }
    public function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return ['exam_id', 'student_id'];
    }

    public function labels(): array
    {
        return [
            'exam_id' => 'Exam ID',
            'student_id' => 'Student ID'
        ];
    }
    

    public static function findAllObjectsByStudentId($student_id): array
    {
        $db = self::getDb();
        $sql = "SELECT * FROM registrations WHERE student_id = :student_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':student_id', $student_id);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public static function findAllObjectsByExamId($exam_id): array
    {
        $db = self::getDb();
        $sql = "SELECT * FROM registrations WHERE exam_id = :exam_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':exam_id', $exam_id);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public function isRegistered($exam_id, $student_id)
    {
        $db = self::getDb();
        $sql = "SELECT * FROM registrations WHERE exam_id = :exam_id AND student_id = :student_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':exam_id', $exam_id);
        $statement->bindValue(':student_id', $student_id);
        $statement->execute();
        $row = $statement->fetchObject(static::class);
        if ($row) {
            return true;
        }
        return false;
    }
}