<?php 

namespace app\models;

use app\database\DbModel;
use app\core\App;
use app\utils\Validation;

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
    public $user_id;

    public function rules(): array
    {
        return [
            'name' => [Validation::RULE_REQUIRED],
            'course_id' => [Validation::RULE_REQUIRED],
            'exam_date' => [Validation::RULE_REQUIRED],
            'exam_time' => [Validation::RULE_REQUIRED],
            'exam_place' => [Validation::RULE_REQUIRED],
            'exam_duration' => [Validation::RULE_REQUIRED],
        ];
    }
    
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

    public static function findAllObjects()
    {
        $statement = DbModel::prepare("SELECT * FROM " . static::tableName());
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
    

    public function save()
    {
        $this->created_by = App::$app->user->id;
        $this->created_at = date('Y-m-d H:i:s');
        $this->exam_date = date('Y-m-d', strtotime($this->exam_date));
        $this->exam_time = date('H:i:s', strtotime($this->exam_time));
        $this->exam_duration = date('H:i:s', strtotime($this->exam_duration));
        return parent::save();
    }

    public function getCreator(): ?string
    {
        $db = (new ExamsModel)->getDb();
        $sql = "SELECT firstname, lastname FROM users WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':id', $this->created_by);
        $statement->execute();
        $row = $statement->fetchObject();
    
        if (!$row) {
            return null;
        }
    
        return $row->firstname . ' ' . $row->lastname;
    }

    public static function findAllByUserId()
    {   
        $db = (new ExamsModel)->getDb();
        $sql = "SELECT * FROM exams WHERE created_by = :user_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':user_id', App::$app->user->id);
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::class);
    }
    
    
    public function isEnrolled($course_id)
    {
        $db = (new ExamsModel)->getDb();
        $sql = "SELECT * FROM enrollment WHERE student_id = :student_id AND course_id = :course_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':course_id', $course_id);
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
        $db = (new ExamsModel)->getDb();
        $SQL = "DELETE FROM exams WHERE id = :id";
        $stmt = $db->prepare($SQL);
        $stmt->bindValue(':id', $this->id);
        return $stmt->execute();
    }
    
    
}
