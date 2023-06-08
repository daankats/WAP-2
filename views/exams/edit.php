<?php 

$this->title = 'Examen bewerken';

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

?>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center"><?= $this->title ?></h1>
        <?php if ($session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?= $session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <form method="post" action="/exams/update?id=<?= $id ?>">
            <div class="form-group">
                <label for="name">Naam</label>
                <input type="text" id="name" name="name" value="<?= $model->name ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="course_id">Cursus</label>
                <select id="course_id" name="course_id" required class="form-control">
                    <?php foreach ($model->getCourses() as $key => $value) : ?>
                        <option value="<?= $key ?>" <?= $key == $model->course_id ? 'selected' : '' ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="exam_place">Examenplaats</label>
                <input type="text" id="exam_place" required name="exam_place" value="<?= $model->exam_place ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="exam_date">Datum</label>
                <input type="date" id="exam_date" required name="exam_date" value="<?= $model->exam_date ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="exam_time">Tijd</label>
                <input type="time" id="exam_time" required name="exam_time" value="<?= $model->exam_time ?>" class="form-control">
            </div>
            <div class="form-group">
                <label for="exam_duration">Tijdsduur</label>
                <input type="time" id="exam_duration" required name="exam_duration" value="<?= $model->exam_duration ?>" class="form-control">
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Examen bewerken</button>
            </div>
        </form>
    </div>
</div>
