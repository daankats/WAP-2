<?php
/** @var $model \app\models\User
 */

use app\core\form\Form;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if ($model->validate() && $model->register()) {
        header('Location: /home');
        exit;
    }
}

?>

<h1>Create account</h1>

<!-- create register form -->
<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'firstName') ?>
    <?php echo $form->field($model, 'lastName') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'confirmPassword')->passwordField() ?>
    <br>
    <button type="submit" class="btn btn-primary">Account maken</button>

<?php Form::end() ?>
