<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\core\App;

$app = new App(dirname(__DIR__), $_ENV);

<<<<<<< HEAD
<<<<<<< HEAD
=======
// Laad de routes
require_once __DIR__ . '/app/routes/routes.php';
>>>>>>> parent of 8861080 (no message)
=======
// Laad de routes
require_once __DIR__ . '/app/routes/routes.php';
>>>>>>> f3833bb6c855df2aac1677c84f01df6f994a58f6

$app->run();
