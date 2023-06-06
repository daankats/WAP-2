<?php

?>

<h1>Mijn profiel</h1>

<div class="row">
    <div class="col-md-6">
        <p><strong>Name:</strong> <?php echo $user->firstName . ' ' . $user->lastName ?></p>
        <p><strong>Email:</strong> <?php echo $user->email ?></p>
        <p><strong>Role:</strong> <?php echo $user->role ?></p>
    </div>
</div>
