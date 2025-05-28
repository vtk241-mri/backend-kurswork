<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--artists-edit">
    <h1 class="title title--section">Редагувати виконаця</h1>
    <form action="/admin/artists/<?= htmlspecialchars($artist['id'], ENT_QUOTES) ?>/update" method="POST"
        enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label>Ім'я виконаця</label>
            <input type="text" name="name" class="form-control" required
                value="<?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>">
        </div>
        <div class="form-group">
            <label>Біографія</label>
            <textarea name="bio" class="form-control"
                rows="4"><?= htmlspecialchars($artist['bio'], ENT_QUOTES) ?></textarea>
        </div>
        <div class="form-group">
            <label>Поточне фото</label><br>
            <?php if (!empty($artist['image'])): ?>
                <img src="<?= htmlspecialchars($artist['image'], ENT_QUOTES) ?>" alt=""
                    style="max-width:150px; display:block; margin-bottom:10px;">
            <?php else: ?>
                <p>Немає фото</p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label>Завантажити нове фото</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="button admin-btn">Зберегти</button>
    </form>
</section>