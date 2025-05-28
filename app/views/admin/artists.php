<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--artists">
    <div class="admin-section__header">
        <h1 class="title title--section">Виконавці</h1>
        <a href="/admin/artists/create" class="button admin-btn">Додати виконавця</a>
    </div>

    <form method="GET" class="admin__search-form search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>" placeholder="Пошук виконавця…"
            class="search-form__input">
        <button type="submit" class="button">Знайти</button>
    </form>

    <div class="admin-artists-grid">
        <?php foreach ($artists as $a): ?>
            <div class="admin-artist-card">
                <div class="admin-artist-card__img">
                    <?php if (!empty($a['image'])): ?>
                        <img src="<?= htmlspecialchars($a['image'], ENT_QUOTES) ?>"
                            alt="Artist <?= htmlspecialchars($a['name'], ENT_QUOTES) ?>">
                    <?php else: ?>
                        <div class="admin-artist-card__placeholder">?</div>
                    <?php endif; ?>
                </div>

                <div class="admin-artist-card__body">
                    <h2 class="admin-artist-card__name">
                        <?= htmlspecialchars($a['name'], ENT_QUOTES) ?>
                    </h2>
                </div>

                <div class="admin-artist-card__actions">
                    <a href="/admin/artists/<?= $a['id'] ?>/edit" class="button admin-btn">Редагувати</a>
                    <form action="/admin/artists/<?= $a['id'] ?>/delete" method="POST" class="inline-form"
                        onsubmit="return confirm('Видалити виконавця?')">
                        <button type="submit" class="button admin-btn">Видалити</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination admin-pagination">
            <?php if ($page > 1): ?>
                <a href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>" class="pagination__link">&laquo;</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="?q=<?= urlencode($q) ?>&page=<?= $p ?>"
                    class="pagination__link <?= $p === $page ? 'is-active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>" class="pagination__link">&raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
</section>