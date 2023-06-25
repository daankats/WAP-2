<?php

namespace app\models;

use app\database\DbModel;
use app\core\App;
use app\utils\Validation;

class EnrollmentModel extends DbModel
{
    public int $id = 0;
    public int $student_id = 0;
    public int $course_id = 0;
    public int $status = 0;
    public string $created_at = '';
    public string $updated_at = '';
    public $user_id;

    public function rules(): array
    {
        return [
            'student_id' => [Validation::RULE_REQUIRED],
            'course_id' => [Validation::RULE_REQUIRED],
            'status' => [Validation::RULE_REQUIRED],

        ];
    }

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
}
