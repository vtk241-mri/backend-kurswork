<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--genres-edit">
    <h1 class="title title--section">Редагувати жанр</h1>

    <form action="/admin/genres/<?= $genre['id'] ?>/update" method="POST" class="admin-form">
        <div class="form-group">
            <label>Нова назва жанру</label>
            <input type="text" name="name" class="form-control"
                value="<?= htmlspecialchars($genre['name'], ENT_QUOTES) ?>" required>
        </div>
        <button type="submit" class="button admin-btn">Зберегти</button>
    </form>
</section>