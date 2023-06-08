<?php

use app\core\App;
use app\core\Auth;

?>


<h1>HOME</h1>
<h3>Welkom <?php $displayName = '';
            if (App::$app->user) {
                $displayName = App::$app->user->displayName();
                echo $displayName;
            } else {
                echo 'bij WAP-2';
            } ?> </h3>
<?php if (Auth::isGuest()) : ?>
    <button class="btn btn-sm btn-success" onclick="window.location.href='/login'">Login</button>
    <button class="btn btn-sm btn-primary" onclick="window.location.href='/register'">Register</button>
<?php endif; ?>

<?php if (Auth::isStudent()) : ?>
    <p>
        als student kan je hier je hier inschrijven voor cursussen en je cijfers bekijken. <br> Voor je cursus krijg je ook examens, in dit systeem kun je je ook registreren voor een examen als je bent ingeschreven voor de bijbehorende cursus.
    </p>
    <button class="btn btn-sm btn-primary" onclick="window.location.href='/courses'">Cursussen</button>
    <button class="btn btn-sm btn-primary" onclick="window.location.href='/exams'">Examens</button>
    <button class="btn btn-sm btn-success" onclick="window.location.href='/myprogress'">Cijfers</button>
<?php endif; ?>

<?php if (Auth::isTeacher()) : ?>
    <p>
        als docent kun je hier je cursussen en examens beheren. <br> Je kunt ook de cijfers van je studenten invoeren en bekijken.
    </p>

    <button class="btn btn-sm btn-success" onclick="window.location.href='/courses'">Cursussen</button>
    <button class="btn btn-sm btn-success" onclick="window.location.href='/exams'">Examens</button>
<?php endif; ?>

<?php if (Auth::isAdmin()) : ?>
    <p>
        als beheerder kun je hier de docenten en studenten beheren. <br> Je kunt ook de cursussen en examens beheren.
    </p>
    <button class="btn btn-sm btn-success" onclick="window.location.href='/users'">Gebruikers</button>
    <button class="btn btn-sm btn-primary" onclick="window.location.href='/courses'">Cursussen</button>
    <button class="btn btn-sm btn-primary" onclick="window.location.href='/exams'">Examens</button>
    <button class="btn btn-sm btn-danger" onclick="window.location.href='/admin'">Admin paneel</button>
<?php endif; ?>