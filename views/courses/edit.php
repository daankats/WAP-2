<?php
$this->title = 'Edit Course';

use app\core\form\Form;
use app\core\App;
use app\models\CourseModel;
use app\models\User;

$session = App::$app->session;

$id = $_GET['id'] ?? null;
if (!$id) {
    $session->setFlash('error', 'Course ID not provided.');
    header('Location: /courses');
    exit;
}

$model = CourseModel::findOne(['id' => $id]);
if (!$model) {
    $session->setFlash('error', 'Course not found.');
    header('Location: /courses');
    exit;
}


$form = Form::begin('', 'post');
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center"><?= $this->title ?></h1>
        <?php if ($session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?= $session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'code') ?>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>

<?php Form::end() ?>
