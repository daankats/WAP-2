<?php

use app\core\Auth;
use app\core\App;

$app = App::$app;
$user = $app->user;
$session = $app->session;

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title  ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark min-vh-100">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <span class="fs-4">WAP-2</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link" aria-current="page">
                            Dashboard
                        </a>
                    </li>
                    <?php if (Auth::isGuest()) : ?>

                    <?php else : ?>

                        <?php if (Auth::isStudent()) : ?>

                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/myprogress">Mijn voortgang</a>
                            </li>
                        <?php elseif (Auth::isTeacher()) : ?>
                            <li class="nav-item">
                                <a class="nav-link" aria-current="page" href="/exams/results">Resultaten</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/courses">Cursussen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="/exams">Examens</a>
                        </li>

                        <?php if (Auth::isAdmin()) : ?>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin">Admin</a>
                            </li>
                        <?php endif; ?>
                </ul>
                <hr>


                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong><?php $displayName = '';
                                if ($user) {
                                    $displayName = App::$app->user->displayName();
                                    echo $displayName;
                                } else {
                                    echo 'Gast';
                                } ?>
                        <?php endif; ?>
                        </strong>
                    </a>
                    <?php if (!Auth::isGuest()) : ?>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="/profile">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="/logout">Log uit</a></li>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
            <div class="col-auto col-md-9 col-xl-10 py-md-3 pl-md-5 bd-content">
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


                {{content}}
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>