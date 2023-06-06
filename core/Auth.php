<?php

namespace app\core;

use app\models\UserModel;

class Auth
{
    public static function isGuest()
    {
        return !App::$app->user;
    }

    public static function isStudent()
    {
        return App::$app->user->role === 'student';
    }

    public static function isAdmin()
    {
        return App::$app->user->role === 'admin';
    }

    public static function isTeacher()
    {
        return App::$app->user->role === 'teacher';
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
