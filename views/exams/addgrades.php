<?php
use app\core\App;
use app\models\Exam;
use app\models\ExamsModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\GradesModel;

$this->title = 'Cijfer toevoegen';

$exam_id = $_GET['id']; // assuming you're passing the exam ID through the URL

$exam = ExamsModel::findOne(['id' => $exam_id]);

$registrations = RegisterModel::findAll();



if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h1>Examen: <?= $exam->name ?></h1>
<a href="/exams" class="btn btn-primary mb-3">Terug</a>
<table class="table">
    <thead>
        <tr>
            <th>Student</th>
            <th>Exam</th>
            <th>Grade</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($registrations as $registration) : ?>
            <?php $student = User::findOne(['id' => $registration->student_id]) ?>

            <?php
            // Get the grade for the current student and exam combination
            $grade = GradesModel::findOne(['exam_id' => $exam->id, 'user_id' => $student->id]);
            ?>
            <tr>
                <td><?= $student->firstName . ' ' . $student->lastName ?></td>
                <td><?= $exam->name ?></td>

                <td>
                    <form action="<?= isset($grade->grade) ? '/exams/updategrade' : '/exams/addgrades' ?>" method="post">
                    <input type="hidden" name="id" value="<?= $grade->id ?? null ?>">
                        <input type="hidden" name="exam_id" value="<?= $exam->id ?>">
                        <input type="hidden" name="student_id" value="<?= $student->id ?>">
                        <input type="text" min="1" max="10" step="0.1" name="grade" required value="<?= isset($grade->grade) ? $grade->grade : '' ?>">
                        <button type="submit" class="btn btn-primary"><?= isset($grade->grade) ? 'Cijfer wijzigen' : 'Cijfer toevoegen' ?></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
