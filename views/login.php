<?php
/** @var $model \app\models\UserModel
 */

?>
<h1>Inloggen</h1>

<?php if (!empty($errorMessage)) : ?>
    <div class="alert alert-danger"><?= $errorMessage ?></div>
<?php endif; ?>

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