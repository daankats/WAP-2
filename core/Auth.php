<?php

namespace app\core;

use app\models\UserModel;
use app\core\Session;

class Auth
{
    public static function isGuest(): bool
    {
        return !App::$app->user;
    }

    public static function isStudent(): bool
    {
        return App::$app->user->role === 'student';
    }

    public static function isAdmin(): bool
    {
        return App::$app->user->role === 'beheerder';
    }

    public static function isTeacher(): bool
    {
        return App::$app->user->role === 'docent';
    }

    public static function login(UserModel $user)
    {
        App::$app->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        App::$app->session->set('user', $primaryValue);
    }

    public static function logout()
    {
        App::$app->user = null;
        App::$app->session->remove('user');
    }
}
