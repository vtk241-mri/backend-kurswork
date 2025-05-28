<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--users-edit">
    <h1 class="title title--section">Редагувати користувача</h1>

    <form action="/admin/users/<?= $user['id'] ?>/update" method="POST" class="admin-form">
        <div class="form-group">
            <label>Ім'я користувача</label>
            <input type="text" name="username" class="form-control"
                value="<?= htmlspecialchars($user['username'], ENT_QUOTES) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control"
                value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" required>
        </div>
        <div class="form-group">
            <label>Роль</label>
            <select name="role" class="form-control">
                <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="button admin-btn">Зберегти</button>
    </form>
</section>