<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--artists-create">
    <h1 class="title title--section">Додати виконаця</h1>
    <form action="/admin/artists" method="POST" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label>Ім'я виконаця</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Біографія</label>
            <textarea name="bio" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label>Фото виконаця</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="button admin-btn">Додати</button>
    </form>
</section>