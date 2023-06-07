<?php
/** @var $model \app\models\UserModel
 */

use app\core\App;
use app\core\Auth;

$session = App::$app->session;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if ($model->validate() && $model->register()) {
        $session->setFlash('success', 'Account created successfully.');
        header('Location: /dashboard');
        exit;
    } else{
        $session->setFlash('error', 'er ging iets mis. Probeer het opnieuw.');
    }
}
?>
<h1>Nieuwe gebruiker aanmaken</h1>
<!-- create register form -->
<form method="POST" action="/registerPost">
    <div class="form-group">
        <label for="firstName">Voornaam</label>
        <input type="text" class="form-control"  id="firstName" name="firstName" value="<?php echo htmlspecialchars($model->firstName) ?>" required>
    </div>
    <div class="form-group">
        <label for="lastName">Achternaam</label>
        <input type="text" class="form-control"  id="lastName" name="lastName" value="<?php echo htmlspecialchars($model->lastName) ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Emailadres</label>
        <input type="email" class="form-control"   id="email" name="email" value="<?php echo htmlspecialchars($model->email) ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" class="form-control"  id="password" name="password" required>
    </div>
    <div class="form-group">
        <label for="confirmPassword">Wachtwoord bevestigen</label>
        <input type="password" class="form-control"  id="confirmPassword" name="confirmPassword" required>
    </div>
    <div class="form-group">
        <label for="role">Rol</label>
        <select class="form-control" required id="role" name="role">
            <option value="student" <?php echo $model->role === 'student' ? 'selected' : '' ?>>Student</option>
            <?php if (Auth::isAdmin()) { ?>
            <option value="docent" <?php echo $model->role === 'docent' ? 'selected' : '' ?>>Docent</option>
            <option value="beheerder" <?php echo $model->role === 'beheerder' ? 'selected' : '' ?>>Beheerder</option>
            <?php } ?>
        </select>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Aanmaken</button>
</form>


