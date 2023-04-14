<h1>Gebruiker wijzigen</h1>
<form method="post">
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= $user->email ?>" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="">Select a role</option>
            <option value="admin" <?= $user->role === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="user" <?= $user->role === 'user' ? 'selected' : '' ?>>User</option>
        </select>
    </div>
    <button type="submit">Save</button>
</form>
