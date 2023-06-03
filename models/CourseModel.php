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
        $enrollments = EnrollmentModel::findAll(['course_id' => $this->id]);
        foreach ($enrollments as $enrollment) {
            $enrollment->delete();
        }
        return parent::delete();
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
        $sql = "SELECT * FROM " . self::tableName() . " WHERE created_by = :id";
        $stmt = App::$app->db->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    public function getCreator(): ?string
    {
        $user = User::findOne(['id' => $this->created_by]);
        return $user ? $user->getFullName() : null;
    }

    public function isEnrolled($student_id)
    {
        $enrollment = EnrollmentModel::findOne(['student_id' => $student_id, 'course_id' => $this->id]);
        return $enrollment ? true : false;
    }

    public function getEnrolledStudents()
    {
        return EnrollmentModel::findAll(['course_id' => $this->id]);
    }
}
