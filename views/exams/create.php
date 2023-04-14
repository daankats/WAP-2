<?php

$this->title = 'Voeg examen toe';

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

?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center"><?= $this->title ?></h1>
        <?php if ($session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?= $session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Naam</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= $model->name ?>">
            </div>
            <div class="form-group">
                <label for="course_id">Cursus</label>
                <select class="form-control" id="course_id" required name="course_id">
                    <?php foreach ($model->getCourses() as $course_id => $course_name) : ?>
                        <option value="<?= $course_id ?>" <?= $model->course_id == $course_id ? 'selected' : '' ?>>
                            <?= $course_name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exam_place">Locatie</label>
                <input type="text" required class="form-control" id="exam_place" name="exam_place" value="<?= $model->exam_place ?>">
            </div>
            <div class="form-group">
                <label for="exam_date">Datum</label>
                <input type="date" required class="form-control" id="exam_date" name="exam_date" value="<?= $model->exam_date ?>">
            </div>
            <div class="form-group">
                <label for="exam_time">Tijd</label>
                <input type="time" required class="form-control" id="exam_time" name="exam_time" value="<?= $model->exam_time ?>">
            </div>
            <div class="form-group">
                <label for="exam_duration">Tijdsduur</label>
                <input type="time" required class="form-control" id="exam_duration" name="exam_duration" value="<?= $model->exam_duration ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Examen toevoegen</button>
            </div>
        </form>
    </div>
</div>
