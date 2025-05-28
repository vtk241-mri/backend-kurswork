<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--tracks">
    <h1 class="title title--section">Управління треками</h1>

    <form method="GET" class="search-form admin__search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>"
            placeholder="Пошук за назвою чи виконавцем…" class="search-form__input">
        <button type="submit" class="button">Знайти</button>
    </form>

    <div class="admin-tracks-grid">
        <?php foreach ($tracks as $t): ?>
            <div class="admin-track-card">
                <?php if (!empty($t['cover_image'])): ?>
                    <div class="admin-track-card__img">
                        <img src="<?= htmlspecialchars($t['cover_image'], ENT_QUOTES) ?>"
                            alt="Cover <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>">
                    </div>
                <?php endif; ?>

                <div class="admin-track-card__body">
                    <h2 class="admin-track-card__title">
                        <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>
                    </h2>
                    <p class="admin-track-card__meta">
                        <strong>ID:</strong> <?= $t['id'] ?><br>
                        <strong>Виконавці:</strong>
                        <?= htmlspecialchars($t['artist_list'] ?? '—', ENT_QUOTES) ?><br>
                        <strong>Жанри:</strong>
                        <?= htmlspecialchars($t['genre_list'] ?? '—', ENT_QUOTES) ?><br>
                        <strong>Дата:</strong> <?= htmlspecialchars($t['created_at'] ?? '', ENT_QUOTES) ?>
                    </p>
                </div>

                <div class="admin-track-card__actions">
                    <a href="/admin/tracks/<?= $t['id'] ?>/edit" class="button admin-btn">Редагувати</a>
                    <form action="/admin/tracks/<?= $t['id'] ?>/delete" method="POST" class="inline-form"
                        onsubmit="return confirm('Видалити трек?')">
                        <button type="submit" class="button admin-btn">Видалити</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- пагінація -->
    <?php if ($totalPages > 1): ?>
        <nav class="pagination admin-pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="pagination__link">&laquo;</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="?page=<?= $p ?>" class="pagination__link <?= $p === $page ? 'is-active' : '' ?>">
                    <?= $p ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="pagination__link">&raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</section>