<?php
/** @var $model \app\models\User
 */

use app\core\App;

$session = App::$app->session;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if (App::isAdmin() && $model->validate() && $model->register()) {
        $session->setFlash('success', 'Account created successfully.');
        header('Location: /home');
        exit;
    } elseif (!App::isAdmin()) {
        $session->setFlash('error', 'You do not have permission to create an account.');
    }
}
?>
<h1>Nieuwe gebruiker aanmaken</h1>
<!-- create register form -->
<form method="POST">
    <div class="form-group">
        <label for="firstName">Voornaam</label>
        <input type="text" class="form-control" required id="firstName" name="firstName" value="<?php echo htmlspecialchars($model->firstName) ?>" required>
    </div>
    <div class="form-group">
        <label for="lastName">Achternaam</label>
        <input type="text" class="form-control" required id="lastName" name="lastName" value="<?php echo htmlspecialchars($model->lastName) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Emailadres</label>
        <input type="email" class="form-control"  required id="email" name="email" value="<?php echo htmlspecialchars($model->email) ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" class="form-control" required id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="confirmPassword">Wachtwoord bevestigen</label>
        <input type="password" class="form-control" required id="confirmPassword" name="confirmPassword" required>
    </div>
    <div class="form-group">
        <label for="role">Rol</label>
        <select class="form-control" required id="role" name="role">
            <option value="student" <?php echo $model->role === 'student' ? 'selected' : '' ?>>Student</option>
            <option value="docent" <?php echo $model->role === 'docent' ? 'selected' : '' ?>>Docent</option>
            <option value="beheerder" <?php echo $model->role === 'beheerder' ? 'selected' : '' ?>>Beheerder</option>
        </select>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Aanmaken</button>
</form>


