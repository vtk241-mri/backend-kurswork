<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--genres">
    <div class="admin-section__header">
        <h1 class="title title--section">Жанри</h1>
        <a href="/admin/genres/create" class="button admin-btn">Додати жанр</a>
    </div>

    <div class="admin-genres-grid">
        <?php foreach ($genres as $g): ?>
            <div class="admin-genre-card">
                <div class="admin-genre-card__info">
                    <span class="admin-genre-card__id">#<?= htmlspecialchars($g['id'], ENT_QUOTES) ?></span>
                    <h2 class="admin-genre-card__name"><?= htmlspecialchars($g['name'], ENT_QUOTES) ?></h2>
                </div>
                <div class="admin-genre-card__actions">
                    <a href="/admin/genres/<?= $g['id'] ?>/edit" class="button admin-btn">Редагувати</a>
                    <form action="/admin/genres/<?= $g['id'] ?>/delete" method="POST" class="inline-form"
                        onsubmit="return confirm('Видалити жанр?')">
                        <button type="submit" class="button admin-btn">Видалити</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>