<?php
use app\core\App;

?>

<h1>HOME</h1>
<h3>Welkom <?php $displayName = '';
    if (App::$app->user) {
        $displayName = App::$app->user->displayName();
        echo $displayName;
    } else {
        echo 'Guest';
    } ?></h3>
