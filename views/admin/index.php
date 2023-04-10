<h1>Users</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user->id ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->role ?></td>
                <td><a href="<?= '/admin/edit?id=' . $user->id ?>">Edit</a></td>
                <form method="post" action="/admin/delete">
    <input type="hidden" name="id" value="<?= $user->id ?>">
    <button type="submit">Delete</button>
</form>

            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
