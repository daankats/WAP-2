<?php

$this->title = 'Admin dashboard';
?>

<h1>Gebruikers</h1>

    <div class="row p-3">
        <div class="col-12">
        <a href="/register" class="btn btn-primary">Nieuwe gebruiker toevoegen</a>
    </div>
    </div>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Actie</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->role ?></td>
                <td><button class="btn btn-primary"><a style="color:white;" href="<?= '/admin/edit?id=' . $user->id ?>">Wijzigen</a></button>
                <form method="post" action="/admin/delete">
    <input type="hidden" name="id" value="<?= $user->id ?>">
    <button class="btn btn-danger" type="submit">Verwijderen</button>
</form></td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
