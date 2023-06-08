<?php

use app\core\App;
use app\models\CourseModel;

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

?>
<h1 class="text-center">Cursus bewerken</h1>
<div class="row">
    <div class="col-md-6 offset-md-3">
        <h1 class="text-center"><?= $this->title ?></h1>
        <?php if ($session->getFlash('error')) : ?>
            <div class="alert alert-danger">
                <?= $session->getFlash('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="/courses/update?id=<?= $id ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" required id="name" name="name" class="form-control" value="<?= $model->name ?>">
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" required id="code" name="code" class="form-control" value="<?= $model->code ?>">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
