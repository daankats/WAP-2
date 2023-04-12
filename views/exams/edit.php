<?php 

$this->title = 'Examen bewerken';

use app\core\form\Form;
use app\core\App;
use app\models\ExamsModel;

$session = App::$app->session;

$id = $_GET['id'] ?? null;
if (!$id) {
    $session->setFlash('error', 'Course ID not provided.');
    header('Location: /courses');
    exit;
}

$model = ExamsModel::findOne(['id' => $id]);
if (!$model) {
    $session->setFlash('error', 'Course not found.');
    header('Location: /courses');
    exit;
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
            <button type="submit" class="btn btn-primary">Examen bewerken</button>
        </div>
        <?php Form::end() ?>
    </div>
</div>
