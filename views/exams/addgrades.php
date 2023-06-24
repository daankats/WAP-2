<?php

use app\core\App;
use app\models\ExamsModel;
use app\models\RegisterModel;
use app\models\UserModel;
use app\models\GradesModel;

$exam_id = $_GET['id'];

$exam = ExamsModel::findOne(['id' => $exam_id]);

$registrations = RegisterModel::findAllObjectsByExamId($exam_id);



if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) : ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h1 class="text-center">Examen: <?= $exam->name ?></h1>
<a href="/exams" class="btn btn-primary mb-3">Terug</a>
<table class="table">
    <thead>
        <tr>
            <th>Student</th>
            <th>Examen</th>
            <th>Cijfer</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registrations as $registration) : ?>
            <?php
            $student = UserModel::findOne(['id' => $registration->student_id]);
            $grade = GradesModel::findOne(['exam_id' => $exam->id, 'user_id' => $student->id]);
            ?>
            <tr>
                <td><?= $student->firstName . ' ' . $student->lastName ?></td>
                <td><?= $exam->name ?></td>

                <td>
                    <form action="<?= isset($grade->grade) ? '/exams/updategrade' : '/exams/addgrades' ?>" method="post">
                        <input type="hidden" name="id" required value="<?= $grade->id ?? 0 ?>">
                        <input type="hidden" name="exam_id" required value="<?= $exam->id ?>">
                        <input type="hidden" name="student_id" required value="<?= $student->id ?>">
                        <input type="text" min="1" max="2" step="0.1" name="grade" required value="<?= isset($grade->grade) ? $grade->grade : '' ?>">
                        <button type="submit" class="btn btn-primary"><?= isset($grade->grade) ? 'Cijfer wijzigen' : 'Cijfer toevoegen' ?></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>