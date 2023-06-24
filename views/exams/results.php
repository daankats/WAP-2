<?php

use app\core\App;
use app\models\ExamsModel;
use app\models\UserModel;
use app\models\GradesModel;
use app\core\Auth;

$exams = [];

if (Auth::isAdmin()) {
    $exams = ExamsModel::findAllObjects();
} else {
    $user_id = App::$app->user->id;
    $exams = ExamsModel::findAllByUserId(['created_by' => $user_id]);
}

if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) : ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<h1 class="text-center">Tentamenresultaten</h1>
<a href="/exams" class="btn btn-primary mb-3">Terug</a>

<form method="GET" action="">
    <div class="form-row mb-3">
        <div class="col-auto">
            <label for="filter-exam">Filter op examen:</label>
        </div>
        <div class="col-auto">
            <select class="form-control" id="filter-exam" name="filter-exam">
                <option value="">Alle examens</option>
                <?php foreach ($exams as $exam) : ?>
                    <option value="<?= $exam->id ?>" <?= isset($_GET['filter-exam']) && $_GET['filter-exam'] == $exam->id ? 'selected' : '' ?>><?= $exam->name ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filteren</button>
        </div>
    </div>
</form>

<form method="GET" action="">
    <div class="form-row mb-3">
        <div class="col-auto">
            <label for="filter">Filter op naam of ID:</label>
        </div>
        <div class="col-auto">
            <input type="text" class="form-control" id="filter" name="filter" value="<?= isset($_GET['filter']) ? htmlspecialchars($_GET['filter']) : '' ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Filteren</button>
        </div>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Tentamen</th>
            <th>Student</th>
            <th>ID Nummer</th>
            <th>Cijfer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exams as $exam) : ?>
            <?php
            $grades = GradesModel::findAll(['exam_id' => $exam->id]);
            foreach ($grades as $grade) :
                $student = UserModel::findOne(['id' => $grade->user_id]);

                // Filter
                $filterExam = isset($_GET['filter-exam']) ? $_GET['filter-exam'] : '';
                $filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : '';
                $studentName = strtolower($student->firstName . ' ' . $student->lastName);
                $studentId = strtolower($student->id);

                if (!empty($filterExam) && $filterExam != $exam->id) {
                    continue;
                }

                if (!empty($filter) && (strpos($studentName, $filter) === false && strpos($studentId, $filter) === false)) {
                    continue;
                }
            ?>
                <tr>
                    <td><?= $exam->name ?></td>
                    <td><?= $student->firstName . ' ' . $student->lastName ?></td>
                    <td><?= $student->id ?></td>
                    <td><?= $grade->grade ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>