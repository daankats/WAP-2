<?php
use app\core\App;
use app\core\Auth;

?>

<h1 class="text-center"><?= $this->title ?></h1>

<?php if (Auth::isTeacher() || Auth::isAdmin()): ?>
    <p><a href="/courses/create" class="btn btn-primary">Voeg cursus toe</a></p>
<?php endif ?>

<table class="table">
    <thead>
        <tr>
            <th>Naam</th>
            <th>Code</th>
            <th>Aangemaakt door</th>
            <th>Aangemaakt op</th>
            <th>Actie</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course->name ?></td>
            <td><?= $course->code ?></td>
            <td><?= $course->getCreator() ?></td>
            <td><?= date('d-m-Y H:i:s', strtotime($course->created_at)) ?></td>
            <td>
                <?php if (Auth::isTeacher() || Auth::isAdmin()): ?>
                    <a href="/courses/edit?id=<?= $course->id ?>" class="btn btn-sm btn-primary">Wijzigen</a>
                    <form action="/courses/delete" method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $course->id ?>">
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Weet u zeker dat u deze cursus wilt verwijderen?')">Verwijderen</button>
                    </form>
                <?php elseif(Auth::isStudent()): ?>
                    <?php if (!$course->isEnrolled($course->id)): ?>
                        <form action="/courses/enroll" method="post" style="display:inline">
                            <input type="hidden" name="course_id" value="<?= $course->id ?>">
                            <button type="submit" class="btn btn-sm btn-success">Inschrijven</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-sm btn-info" disabled>Ingeschreven</button>
                        <form action="/courses/leave" method="post" style="display:inline">
                            <input type="hidden" name="course_id" value="<?= $course->id ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Uitschrijven</button>
                        </form>
                    <?php endif ?>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
