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

<h3 style="color:red;">Let op inlog formulier zit soms onder de pagina, nog een kleine bug.</h3>
<p>
    Om de database te configureren, dien je de inhoud van het bestand .env.example te bekijken en op basis daarvan een
    nieuw bestand genaamd .env aan te maken met de juiste gegevens. Daarna dien je het bestand migrations.php uit te
    voeren met PHP om de database en tabellen aan te maken.
    Vervolgens moet je een account aanmaken in de database en deze voorzien van de rol 'beheerder'. Met dit account kun
    je andere gebruikers aanmaken.
</p>