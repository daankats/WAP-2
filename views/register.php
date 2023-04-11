<?php
/** @var $model \app\models\User
 */

use app\core\form\Form;
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
<!-- create register form -->
<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'firstName') ?>
    <?php echo $form->field($model, 'lastName') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'confirmPassword')->passwordField() ?>
    <?php echo $form->field($model, 'role')->dropdownField([
        'student' => 'Student',
        'docent' => 'Docent',
        'beheerder' => 'Beheerder'
    ]) ?>
    <br>
    <button type="submit" class="btn btn-primary">Aanmaken</button>
<?php Form::end() ?>

