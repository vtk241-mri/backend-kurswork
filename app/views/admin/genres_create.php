<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--genres-create">
    <h1 class="title title--section">Додати новий жанр</h1>

    <form action="/admin/genres" method="POST" class="admin-form">
        <div class="form-group">
            <label>Назва жанру</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <button type="submit" class="button admin-btn">Додати</button>
    </form>
</section>