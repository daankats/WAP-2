<?php 

namespace app\models;

use app\core\db\DbModel;
use app\core\App;

class ExamsModel extends DbModel
{
    public int $id = 0;
    public string $name = '';
    public int $course_id = 0;
    public int $created_by = 0;
    public string $created_at = '';
    public string $exam_date = '';
    public string $exam_time = '';
    public string $exam_place = '';
    public string $exam_duration = '';
    public string $start_time = '';
    public int $duration = 0; 
    public string $updated_at = '';
    public int $updated_by = 0;
    
    public function primaryKey(): string
    {
        return 'id';
    }

    public static function tableName(): string
    {
        return 'exams';
    }

    public function attributes(): array
    {
        return ['name', 'course_id', 'created_by', 'created_at', 'exam_date', 'exam_time', 'exam_place', 'exam_duration', 'updated_at', 'updated_by'];
    }

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'course_id' => [self::RULE_REQUIRED],
            'created_by' => [self::RULE_REQUIRED],
            'exam_date' => [self::RULE_REQUIRED],
            'exam_time' => [self::RULE_REQUIRED],
            'exam_place' => [self::RULE_REQUIRED],
            'exam_duration' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'name' => 'Naam',
            'course_id' => 'Course ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'exam_date' => 'Examen datum',
            'exam_time' => 'Examen tijd',
            'exam_place' => 'Locatie',
            'exam_duration' => 'Tijdsduur',
        ];
    }

    public function save()
    {
        $this->created_by = App::$app->user->id;
        $this->created_at = date('Y-m-d H:i:s');
        $this->exam_date = date('Y-m-d', strtotime($this->exam_date));
        $this->exam_time = date('H:i:s', strtotime($this->exam_time));
        $this->exam_duration = date('H:i:s', strtotime($this->exam_duration));
        return parent::save();
    }

    public static function findAllObjects()
    {
        $statement = self::prepare("SELECT * FROM " . static::tableName());
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::class);
    }

    public function getCourses()
    {
        $courses = CourseModel::findAllObjects();
        $options = [];
        foreach ($courses as $course) {
            $options[$course->id] = $course->name;
        }
        return $options;
    }

    public function getCourseName()
    {
        $course = CourseModel::findOne(['id' => $this->course_id]);
        return $course->name;
    }    

    public function isRegistered(): bool
    {
        $db = App::$app->db;
        $sql = "SELECT COUNT(*) FROM registrations WHERE student_id = :student_id AND exam_id = :exam_id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':exam_id', $this->id);
        $statement->execute();
        
        return $statement->fetchColumn() > 0;
    }
    

    public function register()
    {
        $db = App::$app->db;
        $sql = "INSERT INTO registrations (exam_id, student_id, created_at) VALUES (:exam_id, :student_id, :created_at)";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':exam_id', $this->id);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':created_at', date('Y-m-d H:i:s'));
        $statement->execute();
    }
    

    public function getCreator(): ?string
    {
        $db = App::$app->db;
        $sql = "SELECT firstname, lastname FROM users WHERE id = :id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':id', $this->created_by);
        $statement->execute();
        $row = $statement->fetchObject();
    
        if (!$row) {
            return null;
        }
    
        return $row->firstname . ' ' . $row->lastname;
    }
    
    public function isEnrollable()
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM enrollment WHERE student_id = :student_id AND course_id = :course_id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':course_id', $this->course_id);
        $statement->execute();
        $row = $statement->fetchObject();
        return $row !== false;
    }
    
    public function update()
    {
        $this->updated_by = App::$app->user->id;
        $this->updated_at = date('Y-m-d H:i:s');
        return parent::update();
    }

    public function delete()
    {
        $db = App::$app->db;
        $SQL = "DELETE FROM exams WHERE id = :id";
        $stmt = $db->pdo->prepare($SQL);
        $stmt->bindValue(':id', $this->id);
        return $stmt->execute();
    }
    
    
}
