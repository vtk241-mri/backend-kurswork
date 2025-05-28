<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--users">
    <div class="admin-section__header">
        <h1 class="title title--section">Користувачі</h1>
        <a href="/admin/users/create" class="button admin-btn">Додати користувача</a>
    </div>

    <form method="GET" class="admin__search-form search-form">
        <input type="text" name="q" value="<?= htmlspecialchars($q, ENT_QUOTES) ?>"
            placeholder="Пошук за ім'ям чи email…" class="search-form__input">
        <button type="submit" class="button">Знайти</button>
    </form>

    <div class="admin-users-grid">
        <?php foreach ($users as $u): ?>
            <div class="admin-user-card">
                <div class="admin-user-card__avatar">
                    <?php if (!empty($u['avatar'])): ?>
                        <img src="<?= htmlspecialchars($u['avatar'], ENT_QUOTES) ?>"
                            alt="Avatar <?= htmlspecialchars($u['username'], ENT_QUOTES) ?>">
                    <?php else: ?>
                        <div class="admin-user-card__avatar--placeholder">?</div>
                    <?php endif; ?>
                </div>

                <div class="admin-user-card__info">
                    <h2 class="admin-user-card__name">
                        <?= htmlspecialchars($u['username'], ENT_QUOTES) ?>
                    </h2>
                    <p class="admin-user-card__email">
                        <?= htmlspecialchars($u['email'], ENT_QUOTES) ?>
                    </p>
                    <p class="admin-user-card__role">
                        Роль: <?= htmlspecialchars($u['role'], ENT_QUOTES) ?>
                    </p>
                </div>

                <div class="admin-user-card__actions">
                    <a href="/admin/users/<?= $u['id'] ?>/edit" class="button admin-btn">Редагувати</a>
                    <form action="/admin/users/<?= $u['id'] ?>/delete" method="POST" class="inline-form"
                        onsubmit="return confirm('Видалити користувача?')">
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