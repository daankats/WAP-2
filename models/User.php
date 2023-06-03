<?php

namespace app\models;

use app\core\db\DbModel;
use app\core\App;

class User extends DbModel
{
    public const STATUS_INACTIVE = 'inactive';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DELETED = 'deleted';
    public int $id = 0;
    public string $created_at = '';
    public string $firstName = '';
    public string $lastName = '';
    public string $email = '';
    public string $status = self::STATUS_INACTIVE;
    public string $password = '';
    public string $confirmPassword = '';
    public string $role = '';

    public static function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'status', 'password', 'role'];
    }

    public function primaryKey(): string
    {
        return 'id';
    }

    public function rules(): array
    {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class]],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }

    public function labels(): array
    {
        return [
            'firstName' => 'Voornaam',
            'lastName' => 'Achternaam',
            'email' => 'Email',
            'password' => 'Wachtwoord',
            'confirmPassword' => 'Wachtwoord herhalen',
        ];
    }

    public function validateConfirmPassword(): bool
    {
        if ($this->password !== $this->confirmPassword) {
            $this->addError('confirmPassword', 'Wachtwoorden komen niet overeen');
            return false;
        }
        return true;
    }

    public function findByEmail(string $email): ?User
    {
        return self::findOne(['email' => $email]);
    }

    public function findById(int $id): ?User
    {
        return self::findOne(['id' => $id]);
    }

    public function register(): bool
    {
        if (App::isAdmin()) {
            $this->status = self::STATUS_ACTIVE;
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            return $this->save();
        } else {
            App::$app->session->setFlash('error', 'You are not authorized to register new users.');
            return false;
        }
    }

    public function displayName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public static function findAllObjects(): array
    {
        $db = App::$app->db;
        $sql = "SELECT * FROM users";
        $statement = $db->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public function delete()
    {
        $tableName = $this->tableName();
        $primaryKey = $this->primaryKey();
        $statement = App::$app->db->prepare("DELETE FROM $tableName WHERE $primaryKey = :id");
        $statement->bindValue(':id', $this->{$primaryKey});
        if (!$statement->execute()) {
            throw new \Exception("Failed to delete record from the database.");
        }
        return true;
    }


    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
