<?php 

namespace app\models;

use app\core\Model;
use app\models\User;
use app\core\App;

class LoginModel extends Model{
    public string $email = '';
    public string $password = '';

    public function rules(): array {
        return [
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    public function labels(): array {
        return [
            'email' => 'Email',
            'password' => 'Password'
        ];
    }

    public function login() {
        $user = User::findOne(['email' => $this->email]);
if (!$user) {
    $this->addError('email', 'User does not exist with this email');
    return false;
}
if (!password_verify($this->password, $user->password)) {
    $this->addError('password', 'Password is incorrect');
    return false;
}
    App::$app->login($user);
    return true;

    }
}