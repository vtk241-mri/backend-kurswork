<section class="search-section">
    <div class="search-form">
        <input type="text" id="artist-search-input" class="search-form__input" placeholder="Пошук виконавця..."
            autocomplete="off">
        <button id="artist-search-clear" class="search-form__btn" type="button">✕</button>
    </div>
</section>

<section class="artists-section">
    <h1 class="title title--section">Виконавці</h1>
    <div id="artists-container" class="artists-list">
        <?php foreach ($artists as $artist): ?>
            <div class="artist-card">
                <?php if (!empty($artist['image'])): ?>
                    <a href="/artists/<?= htmlspecialchars($artist['id'], ENT_QUOTES) ?>">
                        <img src="<?= htmlspecialchars($artist['image'], ENT_QUOTES) ?>"
                            alt="<?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>" class="artist-card__img"></a>
                <?php endif; ?>
                <h2 class="artist-card__name">
                    <a href="/artists/<?= htmlspecialchars($artist['id'], ENT_QUOTES) ?>" class="artist-card__link">
                        <?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>
                    </a>
                </h2>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <nav class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="pagination__link">&laquo; Попередня</a>
            <?php endif; ?>

            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <a href="?page=<?= $p ?>" class="pagination__link <?= $p === $page ? 'is-active' : '' ?>"><?= $p ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="pagination__link">Наступна &raquo;</a>
            <?php endif; ?>
        </nav>
    <?php endif; ?>

</section>