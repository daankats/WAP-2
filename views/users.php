<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->name ?></td>
                <td><?= $user->email ?></td>
                <td><?= $user->role ?></td>
                <td>
                    <a href="/admin/edit-user?id=<?= $user->id ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
