<?php
namespace app\models;

use app\core\App;
use app\database\DbModel;
use app\utils\Validation;

class GradesModel extends DbModel
{
    public int $id = 0;
    public string $user_id = '';
    public string $exam_id = '';
    public string $grade = '';
    public string $created_at = '';
    public string $updated_at = '';
    public string $created_by = '';
    public string $updated_by = '';
    public string $student_id = '';


    public function rules(): array
    {
        return [
            'user_id' => [Validation::RULE_REQUIRED],
            'exam_id' => [Validation::RULE_REQUIRED],
            'grade' => [Validation::RULE_REQUIRED],
        ];
    }
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

    public function labels(): array
    {
        return [
            'user_id' => 'Student',
            'exam_id' => 'Exam',
            'grade' => 'Grade',
        ];
    }

    public static function findAllObjects(): array
    {
        $db = (new GradesModel)->getDb();
        $sql = "SELECT * FROM " . self::tableName();
        $statement = $db->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public function update()
    {
        $db = (new GradesModel)->getDb();
        $sql = "UPDATE " . self::tableName() . " SET grade = :grade WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':grade', $this->grade);
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        return true;
    }

    public static function findAll($user_id)
    {   
        $db = (new GradesModel)->getDb();
        $sql = "SELECT * FROM " . self::tableName() . " WHERE exam_id = :exam_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':exam_id', $user_id['exam_id']);
        $statement->execute();
        $grades = [];
        while ($row = $statement->fetchObject(static::class)) {
            $grades[] = $row;
        }
        return $grades;
    }
    
    
    public function findById(int $id)
    {
        $db = (new GradesModel)->getDb();
        $sql = "SELECT * FROM " . self::tableName() . " WHERE user_id = :user_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':user_id', $id);
        $statement->execute();
        $grades = [];
        while ($row = $statement->fetchObject(static::class)) {
            $grades[] = $row;
        }
        return $grades;
    }
    
    

}
