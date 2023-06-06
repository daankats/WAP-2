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
                echo 'Gast';
            } ?></h3>


<p>
    Om de database te configureren, dien je de inhoud van het bestand .env.example te bekijken en op basis daarvan een nieuw bestand genaamd .env aan te maken met de juiste gegevens. Daarna dien je het bestand migrations.php uit te voeren met PHP om de database en tabellen aan te maken.
    Vervolgens moet je een account aanmaken in de database en deze voorzien van de rol 'beheerder'. Met dit account kun je andere gebruikers aanmaken.
</p>