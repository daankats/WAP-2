<?php

use app\core\Auth;
use app\core\App;

$app = App::$app;
$user = $app->user;
$session = $app->session;

$currentUrl = $_SERVER['REQUEST_URI'];

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="/../css/style.css">

</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">WAP-2</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php if (!Auth::isGuest()) : ?>
            <div class="navbar-nav ml-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo $user->displayName() ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/profile">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">Log uit</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <div class="container-fluid m-3 ">
        <div class="row">
            <div class="col-auto custom-menu col-md-3 col-xl-2 px-0 min-vh-100">
                <hr>
                <ul class="nav nav-pills flex-column mb-auto custom-nav-links">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link <?php echo ($currentUrl == '/dashboard') ? 'active' : ''; ?>" aria-current="page">
                            Dashboard
                        </a>
                    </li>
                    <?php if (Auth::isGuest()) : ?>
                    <?php else : ?>
                        <?php if (Auth::isStudent()) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($currentUrl == '/myprogress') ? 'active' : ''; ?>" aria-current="page" href="/myprogress">Mijn voortgang</a>
                            </li>
                        <?php elseif (Auth::isTeacher() || Auth::isAdmin()) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($currentUrl == '/exams/results') ? 'active' : ''; ?>" aria-current="page" href="/exams/results">Resultaten</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentUrl == '/courses') ? 'active' : ''; ?>" aria-current="page" href="/courses">Cursussen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($currentUrl == '/exams') ? 'active' : ''; ?>" aria-current="page" href="/exams">Examens</a>
                        </li>
                        <?php if (Auth::isAdmin()) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo ($currentUrl == '/admin') ? 'active' : ''; ?>" href="/admin">Admin</a>
                            </li>
                        <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
        <div class="col-auto col-md-9 col-xl-10 bd-content">
            <?php
            $successMessages = $app->session->getFlash('success');
            if (!empty($successMessages)) {
                foreach ($successMessages as $message) {
                    echo '<div class="alert alert-success">' . $message . '</div>';
                }
            }

            $errorMessages = $app->session->getFlash('error');
            if (!empty($errorMessages)) {
                foreach ($errorMessages as $message) {
                    echo '<div class="alert alert-danger">' . $message . '</div>';
                }
            }
            ?>
            <div class="container custom-box">
                {{content}}
            </div>
        </div>
        </div>
    </div>

     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>