<?php

use app\core\App;
use app\models\GradesModel;
use app\models\ExamsModel;
use app\models\UserModel;

/** @var GradesModel[] $grades */
/** @var ExamsModel[] $exams */
/** @var UserModel $user */

$this->title = 'Mijn voortgang';
?>

<h1><?= $this->title; ?></h1>

<a href="/courses" class="btn btn-primary mb-3">Naar cusussen</a>

<a href="/exams" class="btn btn-primary mb-3">Naar examens</a>


<table class="table">
    <thead>
        <tr>
            <th scope="col">Examen</th>
            <th scope="col">Cijfer</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($grades as $grade) : ?>
            <tr>
                <td><?= $exams[$grade->exam_id]->name ?></td>
                <td><?= ($grade->grade === null) ? 'Cijfer komt nog' : $grade->grade ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
