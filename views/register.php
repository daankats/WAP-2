<?php 

use app\core\form\Form;
use app\core\form\Field;
use app\models\RegisterModel;

$model = new RegisterModel();

?>

<h1>Create account</h1>
<!-- create register from -->
<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'firstName') ?>
    <?php echo $form->field($model, 'lastName') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <?php echo $form->field($model, 'confirmPassword')->passwordField() ?>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Registreer</button>
<?php Form::end() ?>