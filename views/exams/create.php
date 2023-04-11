<?php 

$this->title = 'Voeg examen toe';

use app\core\form\Form;
use app\core\App;
use app\models\ExamsModel;

$session = App::$app->session;

$model = new ExamsModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if (App::isAdmin() || App::isDocent()) {
        $model->created_by = App::$app->user->id;
        $model->created_at = date('Y-m-d H:i:s');
        if ($model->validate() && $model->save()) {
            $session->setFlash('success', 'Exam created successfully.');
            header('Location: /exams');
            exit;
        } else {
            $session->setFlash('error', 'Exam creation failed.');
        }
    } else {
        $session->setFlash('error', 'You do not have permission to create an exam.');
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
        Cursus
        <?= $form->field($model, 'course_id')->dropdownField($model->getCourses()) ?>
        <?= $form->field($model, 'exam_place') ?>
        Datum
        <?= $form->field($model, 'exam_date')->dateField() ?>
        Tijd
        <?= $form->field($model, 'exam_time')->timeField() ?>
        Tijdsduur
        <?= $form->field($model, 'exam_duration')->timeField() ?>
        <br>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Examen toevoegen</button>
        </div>
        <?php Form::end() ?>
    </div>
</div>
