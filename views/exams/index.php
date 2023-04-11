<?php
use app\core\App;

$this->title = 'Exams';
?>

<h1>Examens</h1>

<?php if (App::isDocent() || App::isAdmin()) : ?>
    <a href="/exams/create" class="btn btn-primary mb-3">Voeg examen toe</a>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Naam</th>
            <th>Cursus naam</th>
            <th>Aangemaakt door</th>
            <th>Aangemaakt op</th>
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
                <td><?= $exam->created_at ?></td>
                <td><?= $exam->exam_date ?></td>
                <td><?= $exam->exam_time ?></td>
                <td><?= $exam->exam_duration ?> minuten</td>
                <td><?= $exam->exam_place ?></td>
                <td>
                    <?php if (App::isStudent()) : ?>
                        <?php if ($exam->isEnrollable()) : ?>
                            <?php if ($exam->isRegistered()) : ?>
                                <form action="/exams/<?= $exam->id ?>/unregisterexam" method="post" class="d-inline">
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" class="btn btn-sm btn-danger">Uitschrijven</button>
                                </form>
                            <?php else : ?>
                                <form action="/exams/<?= $exam->id ?>/registerexam" method="post" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-primary">Inschrijven</button>
                                </form>
                            <?php endif; ?>
                        <?php else : ?>
                            <p>Je moet ingeschreven zijn voor de bijbehorende cursus om te kunnen inschrijven voor dit examen.</p>
                        <?php endif; ?>
                    <?php elseif (App::isDocent() || App::isAdmin()) : ?>
                        <a href="/exams/<?= $exam->id ?>/edit" class="btn btn-sm btn-primary">Bewerk</a>
                        <form action="/exams/<?= $exam->id ?>" method="post" class="d-inline">
                            <input type="hidden" name="_method" value="delete">
                            <button type="submit" class="btn btn-sm btn-danger">Verwijder</button>
                        </form>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
