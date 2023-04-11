<?php

$this->title = 'Create Course';

use app\core\form\Form;
use app\core\App;
use app\models\CourseModel;

$session = App::$app->session;

$model = new CourseModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if (App::isAdmin() || App::isDocent()) {
        if ($model->validate() && $model->save()) {
            $session->setFlash('success', 'Course created successfully.');
            header('Location: /course');
            exit;
        } else {
            $session->setFlash('error', 'Course creation failed.');
        }
    } else {
        $session->setFlash('error', 'You do not have permission to create a course.');
    }
}

$form = new Form();
?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center"><?= $this->title ?></h1>
        <?php if ($session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?= $session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <?php $form = Form::begin('', 'post') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'code') ?>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
        <?php Form::end() ?>
    </div>
</div>
