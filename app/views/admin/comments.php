<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--comments">
    <h1 class="title title--section">Коментарі</h1>

    <form method="GET" class="admin__search-form search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>"
            placeholder="Пошук по вмісту, користувачу чи треку…" class="search-form__input" />
        <button type="submit" class="button">Знайти</button>
    </form>

    <div class="admin-comments-grid">
        <?php foreach ($comments as $c): ?>
            <div class="admin-comment-card">
                <div class="admin-comment-card__meta">
                    <span class="comment-id">#<?= htmlspecialchars($c['id'], ENT_QUOTES) ?></span>
                    <span class="comment-date"><?= htmlspecialchars($c['created_at'], ENT_QUOTES) ?></span>
                </div>

                <p class="admin-comment-card__user">
                    <strong>Користувач:</strong>
                    <?= htmlspecialchars($c['user_name'], ENT_QUOTES) ?>
                </p>

                <p class="admin-comment-card__track">
                    <strong>Трек:</strong>
                    <?= htmlspecialchars($c['track_title'], ENT_QUOTES) ?>
                </p>

                <div class="admin-comment-card__content">
                    <?= nl2br(htmlspecialchars($c['content'], ENT_QUOTES)) ?>
                </div>

                <div class="admin-comment-card__actions">
                    <form action="/admin/comments/<?= $c['id'] ?>/delete" method="POST" class="inline-form"
                        onsubmit="return confirm('Видалити коментар?')">
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