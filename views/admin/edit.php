<?php 
use app\core\App;
?>

<title><?= $this->title ?> </title>

<?php
$model = new \app\models\User();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if ($model->validate() && $model->update()) {
        App::$app->session->setFlash('success', 'User updated successfully.');
        header('Location: /users');
        exit;
    }
}
?>

<h1>Gebruiker wijzigen</h1>
<form method="post">
    <div class="form-group">
        <label for="firstName">Voornaam</label>
        <input type="text" required id="firstName" name="firstName" value="<?= $user->firstName ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="lastName">Achternaam</label>
        <input type="text" required id="lastName" name="lastName" value="<?= $user->lastName ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" required id="email" name="email" value="<?= $user->email ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" required id="password" name="password" value="<?= $user->password ?>" class="form-control">
    </div>
    <div class="form-group">
        <label for="role">Rol</label>
        <select class="form-control" required id="role" name="role">
            <option value="student" <?php echo $user->role === 'student' ? 'selected' : '' ?>>Student</option>
            <option value="docent" <?php echo $user->role === 'docent' ? 'selected' : '' ?>>Docent</option>
            <option value="beheerder" <?php echo $user->role === 'beheerder' ? 'selected' : '' ?>>Beheerder</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Wijzigen</button>
</form>
