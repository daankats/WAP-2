<?php

namespace app\models;

use app\database\DbModel;
use app\core\app;
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
    
    public static function findAllObjects(): array
    {
        $db = (new RegisterModel)->getDb();
        $sql = "SELECT * FROM registrations";
        $statement = $db->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }
    public static function findAllObjectsByStudentId($student_id): array
    {
        $db = (new RegisterModel)->getDb();
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

    public function isRegistered($exam_id, $student_id)
    {
        $db = (new RegisterModel)->getDb();
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

    public function delete()
    {
        $db = (new RegisterModel)->getDb();
        $sql = "DELETE FROM registrations WHERE exam_id = :exam_id AND student_id = :student_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':exam_id', $this->exam_id);
        $statement->bindValue(':student_id', $this->student_id);
        $statement->execute();
        return true;
    }

    public function getRegisteredExams($student_id)
    {
        $db = (new RegisterModel)->getDb();
        $sql = "SELECT * FROM registrations WHERE student_id = :student_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':student_id', $student_id);
        $statement->execute();
        $exams = [];
        while ($row = $statement->fetchObject(static::class)) {
            $exams[] = $row;
        }
        return $exams;
    }

    public static function findAll()
    {
        $db = (new RegisterModel)->getDb();
        $sql = "SELECT * FROM registrations";
        $statement = $db->prepare($sql);
        $statement->execute();
        $registrations = [];
        while ($row = $statement->fetchObject(static::class)) {
            $registrations[] = $row;
        }
        return $registrations;
    }

}