<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--users-create">
    <h1 class="title title--section">Додати користувача</h1>
    <form action="/admin/users" method="POST" class="admin-form">
        <div class="form-group">
            <label>Ім'я користувача</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Роль</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="button admin-btn">Додати</button>
    </form>
</section>