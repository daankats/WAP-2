<?php

namespace app\models;

use app\database\DbModel;
use app\core\App;
use app\utils\Validation;

class CourseModel extends DbModel
{
    public int $id = 0;
    public string $name = '';
    public string $code = '';
    public string $created_by = '';
    public string $created_at = '';
    public string $updated_by = '';
    public string $updated_at = '';

    public function rules(): array
    {
        return [
            'name' => [Validation::RULE_REQUIRED],
            'code' => [Validation::RULE_REQUIRED],
        ];
    }

    public function primaryKey(): string
    {
        return 'id';
    }
    public static function tableName(): string
    {
        return 'courses';
    }

    public function attributes(): array
    {
        return ['name', 'code', 'created_by', 'created_at', 'updated_by', 'updated_at'];
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


    public function isEnrolled($course_id)
    {
        $db = self::getDb();
        $sql = "SELECT * FROM enrollment WHERE student_id = :student_id AND course_id = :course_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':student_id', App::$app->user->id);
        $statement->bindValue(':course_id', $course_id);
        $statement->execute();
        $row = $statement->fetchObject();
        return $row !== false;
    }

    public function getCreator(): ?string
    {
        $db = self::getDb();
        $sql = "SELECT firstname, lastname FROM users WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':id', $this->created_by);
        $statement->execute();
        $row = $statement->fetchObject();

        if (!$row) {
            return null;
        }

        $user = new UserModel();
        $user->firstName = $row->firstname;
        $user->lastName = $row->lastname;

        return $user->getFullName();
    }


    public function getEnrolledStudents()
    {
        $db = self::getDb();
        $sql = "SELECT * FROM enrollment WHERE course_id = :course_id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':course_id', $this->id);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }
}
