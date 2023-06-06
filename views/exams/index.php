<?php

use app\core\App;
use app\models\RegisterModel;
use app\models\UserModel;
use app\core\Auth;

$registerModel = new RegisterModel();
$user = UserModel::findOne(['id' => App::$app->user->id]);
?>

<h1>Examens</h1>

<?php if (Auth::isTeacher() || Auth::isAdmin()) : ?>
    <a href="/exams/create" class="btn btn-primary mb-3">Voeg examen toe</a>
<?php endif; ?>

<table class="table">
    <thead>
    <tr>
        <th>Naam</th>
        <th>Cursus naam</th>
        <th>Aangemaakt door</th>
        <th>Examen datum</th>
        <th>Examen tijd</th>
        <th>Examen duur</th>
        <th>Examen locatie</th>
        <th>Actie</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($exams as $exam) : ?>
        <tr>
            <td><?= $exam->name ?></td>
            <td><?= $exam->getCourseName() ?></td>
            <td><?= $exam->getCreator() ?></td>
            <td><?= $exam->exam_date ?></td>
            <td><?= $exam->exam_time ?></td>
            <td><?= $exam->exam_duration ?> minuten</td>
            <td><?= $exam->exam_place ?></td>
            <td>
                <?php if (Auth::isStudent()) : ?>
                    <?php if ($exam->isEnrolled($exam->course_id)) : ?>
                        <?php if ($registerModel->isRegistered($exam->id, $user->id)) : ?>
                            <form action="/exams/unregisterexam" method="post" class="d-inline">
                                <input type="hidden" name="exam_id" value="<?php echo $exam->id ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Uitschrijven</button>
                            </form>
                        <?php elseif ($exam->exam_date < date('Y-m-d')) : ?>
                            Examen verlopen
                        <?php else: ?>
                            <form action="/exams/registerexam" method="post" style="display:inline">
                                <input type="hidden" name="exam_id" value="<?php echo $exam->id ?>">
                                <button type="submit" class="btn btn-sm btn-success">inschrijven</button>
                            </form>
                        <?php endif; ?>
                    <?php else : ?>
                        <p>Je moet ingeschreven zijn voor de bijbehorende cursus om te kunnen inschrijven voor dit examen.</p>
                    <?php endif; ?>
                <?php elseif (Auth::isTeacher() || Auth::isAdmin()) : ?>
                    <?php if ($exam->exam_date < date('Y-m-d')) : ?>
                        <a href="/exams/addgrades?id=<?php echo $exam->id ?>" class="btn btn-sm btn-success">Cijfers invoeren</a>
                    <?php endif; ?>
                    <!-- Add button to edit -->
                    <a href="/exams/edit?id=<?php echo $exam->id ?>" class="btn btn-sm btn-primary">Wijzigen</a>
                    <form action="/exams/delete" method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?php echo $exam->id ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je dit examen wilt verwijderen?')">Verwijderen</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
