<?php 

namespace app\models;

use app\core\db\DbModel;
use app\core\App;
use app\models\User;

class CourseModel extends DbModel
{
    public int $id = 0;
    public string $name = '';
    public string $code = '';
    public string $created_by = '';
    public string $created_at = '';
    public string $updated_by = '';
    public string $updated_at = '';

    public function primaryKey(): string
    {
        return 'id';
    }

    public function __construct()
    {
    }

    public static function tableName(): string
    {
        return 'courses';
    }

    public function attributes(): array
    {
        return ['name', 'code', 'created_by', 'created_at', 'updated_by', 'updated_at'];
    }    

    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'code' => [self::RULE_REQUIRED],
            'created_by' => [self::RULE_REQUIRED],
        ];
    }

    public function labels(): array
    {
        return [
            'name' => 'Name',
            'code' => 'Code',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    public function save()
    {
        $this->created_by = App::$app->user->id;
        $this->created_at = date('Y-m-d H:i:s');
        return parent::save();
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
        $SQL = "DELETE FROM courses WHERE id = :id";
        $stmt = $db->pdo->prepare($SQL);
        $stmt->bindValue(':id', $this->id);
        return $stmt->execute();
    }


    public static function findAllObjects(): array
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM courses";
        $statement = $db->pdo->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public static function findCourseByCode($code)
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM courses WHERE code = :code";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':code', $code);
        $statement->execute();
        $row = $statement->fetchObject(static::class);
        return $row;
    }

    public static function findCourseById($id)
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM courses WHERE id = :id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $row = $statement->fetchObject(static::class);
        return $row;
    }

    public function isEnrolled($course_id)
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM enrollment WHERE student_id = :student_id AND course_id = :course_id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        $row = $statement->fetchObject();
        return $row !== false; // return true if a row is found, false otherwise
    }
    

    // get the firstname and lastname of the user who created the course
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
    
        $user = new User();
        $user->firstName = $row->firstname;
        $user->lastName = $row->lastname;
    
        return $user->getFullName();
    }
    

    public function getEnrolledStudents()
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM enrollment WHERE course_id = :course_id";
        $statement = $db->pdo->prepare($sql);
        $statement->bindValue(':course_id', $this->id);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }
    
    
    
}
