<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\core\App;

$app = new App(dirname(__DIR__));

<<<<<<< HEAD
<<<<<<< HEAD
=======
// Laad de routes
<<<<<<< HEAD
require_once __DIR__ . '/../routes/routes.php';
=======
require_once __DIR__ . '/app/routes/routes.php';
>>>>>>> parent of 8861080 (no message)
=======
// Laad de routes
require_once __DIR__ . '/app/routes/routes.php';
>>>>>>> f3833bb6c855df2aac1677c84f01df6f994a58f6
>>>>>>> ce681fc087022242e44b929fb55f939c18a076ee

$app->run();
