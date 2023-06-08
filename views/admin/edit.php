<?php 
use app\core\App;
?>

<title><?= $this->title ?> </title>

<?php
$session = App::$app->session;

$id = $_GET['id'] ?? null;
if (!$id) {
    $session->setFlash('error', 'user ID not provided.');
    header('Location: /courses');
    exit;
}

$user = \app\models\UserModel::findOne(['id' => $id]);
if (!$user) {
    $session->setFlash('error', 'User not found.');
    header('Location: /courses');
    exit;
}

?>

<h1 class="text-center">Gebruiker wijzigen</h1>
<form method="post" action="/admin/update?id=<?= $id ?>">
    <div class="form-group">
        <label for="firstName">Voornaam</label>
        <input type="text"  id="firstName" name="firstName" value="<?= $user->firstName ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="lastName">Achternaam</label>
        <input type="text"  id="lastName" name="lastName" value="<?= $user->lastName ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email"  id="email" name="email" value="<?= $user->email ?>" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password"  id="password" name="password" value="<?= $user->password ?>" class="form-control">
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
