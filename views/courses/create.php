<?php

$this->title = 'Cursus aanmaken';

use app\core\App;
use app\core\Auth;
use app\models\CourseModel;

$session = App::$app->session;

$model = new CourseModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model->loadData($_POST);

    if (Auth::isAdmin() || Auth::isTeacher()) {
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
                <label for="name">Course Name</label>
                <input type="text" required id="name" name="name" class="form-control" value="<?= $model->name ?>" re>
                    
            </div>
            <div class="form-group">
                <label for="code">Course Code</label>
                <input type="text" required id="code" name="code" class="form-control" value="<?= $model->code ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Aanmaken</button>
            </div>
        </form>
    </div>
</div>
