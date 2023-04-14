<?php
use app\core\App;
use app\models\ExamsModel;
use app\models\User;
use app\models\GradesModel;

$this->title = 'Tentamenresultaten';

$user_id = App::$app->user->id;

$exams = ExamsModel::findAllByUserId(['created_by' => $user_id]);

if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h1>Tentamenresultaten</h1>
<a href="/exams" class="btn btn-primary mb-3">Terug</a>
<table class="table">
    <thead>
        <tr>
            <th>Tentamen</th>
            <th>Student</th>
            <th>Cijfer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exams as $exam) : ?>
            <?php
                $grades = GradesModel::findAll(['exam_id' => $exam->id]);
                foreach ($grades as $grade) :
                    $student = User::findOne(['id' => $grade->user_id]);
            ?>
                    <tr>
                        <td><?= $exam->name ?></td>
                        <td><?= $student->firstName . ' ' . $student->lastName ?></td>
                        <td><?= $grade->grade ?></td>
                    </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>
