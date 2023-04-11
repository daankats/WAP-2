<?php
use app\core\App;

$this->title = 'Courses';

if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<h1>Courses</h1>
<?php if (App::isDocent() || App::isAdmin()): ?>
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
        <td><?php echo $course->name ?></td>
        <td><?php echo $course->code ?></td>
        <td><?= $course->getCreator() ?></td>
        <td><?php echo date('d-m-Y H:i:s', strtotime($course->created_at)) ?></td>
        <td>
            <?php if (App::isDocent() || App::isAdmin()): ?>
                <a href="/courses/update?id=<?php echo $course->id ?>" class="btn btn-sm btn-primary">Wijzigen</a>
                <form action="/courses/delete" method="post" style="display:inline">
                    <input type="hidden" name="id" value="<?php echo $course->id ?>">
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Verwijderen</button>
                </form>
            <?php elseif(App::isStudent()): ?>
                <?php if (!$course->isEnrolled($course->id)): ?>
    <form action="/courses/enroll" method="post" style="display:inline">
        <input type="hidden" name="course_id" value="<?php echo $course->id ?>">
        <button type="submit" class="btn btn-sm btn-success">Enroll</button>
    </form>
<?php else: ?>
    <button class="btn btn-sm btn-info" disabled>Enrolled</button>
<?php endif ?>
            <?php endif ?>
        </td>
    </tr>
<?php endforeach ?>
    </tbody>
</table>
