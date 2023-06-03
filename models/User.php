<?php

namespace app\models;

use app\core\DbModel;
use app\core\UserModel;
use app\core\App;

/**
 * Class User
 * @package app\models
 */
class User extends UserModel
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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

    /**
     * @inheritDoc
     */
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
            $this->addError('confirmPassword', 'Wachtwoorden komen niet overeen');
            return false;
        }
        return true;
    }

    /**
     * Finds a user by email.
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return self::findOne(['email' => $email]);
    }

    /**
     * Finds a user by id.
     * @param int $id
     * @return User|null
     */

    public function findById(int $id): ?User
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * Registers a user.
     * @return bool
     */
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
        $db = App::$app->db;
        $sql = "SELECT * FROM users";
        $statement = $db->pdo->prepare($sql);
        $statement->execute();
        $users = [];
        while ($row = $statement->fetchObject(static::class)) {
            $users[] = $row;
        }
        return $users;
    }

    public static function Delete($id)
    {
        $db = App::$app->db;
        $sql = "DELETE FROM users WHERE id = :id";
        $statement = $db->pdo->prepare($sql);
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
