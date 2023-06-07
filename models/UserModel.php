<?php

namespace app\models;

use app\core\Auth;
use app\database\DbModel;
use app\core\App;
use app\utils\Validation;

/**
 * Class UserModel
 * @package app\models
 */
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
            'username' => [Validation::RULE_REQUIRED],
            'email' => [Validation::RULE_REQUIRED, Validation::RULE_EMAIL],
            'password' => [Validation::RULE_REQUIRED],
            'confirmPassword' => [Validation::RULE_REQUIRED],
        ];
    }



    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'status', 'password', 'role'];
    }

    /**
     * @inheritDoc
     */
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

    /**
     * Validates the confirm password field.
     * @return bool
     */
    public function validateConfirmPassword(): bool
    {
        if ($this->password !== $this->confirmPassword) {
            return false;
        }
        return true;
    }

 
    public function findByEmail(string $email): ?UserModel
    {
        return self::findOne(['email' => $email]);
    }


    public function findById(int $id): ?UserModel
    {
        return self::findOne(['id' => $id]);
    }


    public function register(): bool
    {
        if ($this->validateConfirmPassword()) { // Voer wachtwoordvalidatie uit
            $this->status = 'active';
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            return $this->save();
        }

        return false; // Wachtwoordvalidatie is mislukt
    }


    /**
     * Summary of getDisplayName
     * @return string
     */
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
        $db = (new UserModel)->getDb();
        $sql = "SELECT * FROM users";
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
        $db = (new UserModel)->getDb();
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



}
