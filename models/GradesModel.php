<?php
namespace app\models;

use app\core\App;
use app\core\db\DbModel;

class GradesModel extends DbModel
{
    public int $id;
    public string $user_id = '';
    public string $exam_id = '';
    public string $grade = '';
    public string $created_at = '';
    public string $updated_at = '';
    public string $created_by = '';
    public string $updated_by = '';
    public string $student_id = '';


    public static function tableName(): string
    {
        return 'grades';
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function attributes(): array
    {
        return ['user_id', 'exam_id', 'grade'];
    }

    public function rules(): array
    {
        return [
            'user_id' => [self::RULE_REQUIRED],
            'exam_id' => [self::RULE_REQUIRED],
            'grade' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'user_id' => 'Student',
            'exam_id' => 'Exam',
            'grade' => 'Grade',
        ];
    }


    public function getErrors(): array
    {
        return $this->errors;
    }
    public static function findAllObjects(): array
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM " . self::tableName();
        $statement = $db->pdo->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public function update()
    {
        $db = App::$app->db;
        $sql = "UPDATE " . self::tableName() . " SET grade = :grade WHERE id = :id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':grade', $this->grade);
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        return true;
    }

    public function findAll()
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM " . self::tableName();
        $statement = $db->pdo->prepare($sql);
        $statement->execute();
        $grades = [];
        while ($row = $statement->fetchObject(static::class)) {
            $grades[] = $row;
        }
        return $grades;
    }
    

}
