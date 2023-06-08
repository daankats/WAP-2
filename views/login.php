<?php
/** @var $model \app\models\UserModel */

use app\core\Validation;

?>

<h1 class="text-center">Inloggen</h1>

<?php if (!empty($model->errors)) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($model->errors as $attribute => $errors) : ?>
                <?php foreach ($errors as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="alert alert-info">
    <p>Geen account? <a href="/register">Registreer hier</a>.</p>
</div>
<form method="post">
    <div class="form-group">
        <label for="email">E-mail</label>
        <input type="email" name="email" id="email" class="form-control" value="<?php echo $model->email ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Inloggen</button>
</form>
