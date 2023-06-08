<?php

namespace app\models;

use app\core\Auth;
use app\database\DbModel;
use app\core\App;
use app\utils\Validation;


class UserModel extends DbModel
{
    public int $id = 0;
    public string $created_at = '';
    public string $firstName = '';
    public string $username = '';
    public string $lastName = '';

    public string $email = '';
    public string $status = '';
    public string $password = '';
    public string $confirmPassword = '';
    public string $role = '';


    public function rules(): array
    {
        return [
            'email' => [
                Validation::RULE_REQUIRED,
                Validation::RULE_EMAIL,
                [Validation::RULE_UNIQUE, 'class' => UserModel::class, 'attribute' => 'email']
            ],
            'password' => [Validation::RULE_REQUIRED],
            'confirmPassword' => [Validation::RULE_REQUIRED],
        ];
    }


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
            return false;
        }
        return true;
    }


    public function register(): bool
    {
        if ($this->validateConfirmPassword()) {
            $this->status = 'active';
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            return $this->save();
        }

        return false;
    }

    public function displayName(): string {
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
        $db = self::getDb();
        $sql = "SELECT * FROM " . self::tableName();
        $statement = $db->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public static function Delete($id)
    {
        $db = self::getDb();
        $sql = "DELETE FROM users WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }

    public function setUser($data) {
        $this->email = $data['email'] ?? $this->email;
        $this->password = $data['password'] ?? $this->password;
        $this->role = $data['role'] ?? $this->role;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getUser($id){
        $db = self::getDb();
        $sql = "SELECT * FROM users WHERE id = :id";
        $statement = $db->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $user = $statement->fetchObject(static::class);
        return $user;
    }



}
