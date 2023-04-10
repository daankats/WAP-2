<?php
/** @var $model \app\models\User
 */

use app\core\form\Form;


?>

<h1>Inloggen</h1>

<!-- create register form -->
<?php $form = Form::begin('', 'post') ?>
    <?php echo $form->field($model, 'email') ?>
    <?php echo $form->field($model, 'password')->passwordField() ?>
    <br>
    <button type="submit" class="btn btn-primary">Inloggen</button>
<?php Form::end() ?>
