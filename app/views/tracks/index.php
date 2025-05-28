<section class="search-section">
    <div class="search-form">
        <input type="text" id="search-input" class="search-form__input" placeholder="Пошук за назвою або виконавцем…"
            value="<?= htmlspecialchars($q ?? '', ENT_QUOTES) ?>">
        <select id="genre-select" class="search-form__select">
            <option value="0">Усі жанри</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>" <?= ($selectedGenre === (int) $g['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['name'], ENT_QUOTES) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button id="search-clear" class="search-form__btn" type="button">✕</button>
    </div>
</section>

<section id="tracks-container" class="tracks-list">
    <?php foreach ($tracks as $t): ?>
        <div class="track-card">
            <a href="/tracks/<?= $t['id'] ?>">
                <?php if ($t['cover_image']): ?>
                    <img src="<?= htmlspecialchars($t['cover_image'], ENT_QUOTES) ?>" class="track-card__cover">
                <?php endif; ?>
            </a>
            <h2 class="track-card__title">
                <a href="/tracks/<?= $t['id'] ?>">
                    <?= htmlspecialchars($t['title'], ENT_QUOTES) ?></a>
            </h2>
            <p>
                Виконавці:
                <?php
                $artists = (new \App\Models\Track())->getArtists((int) $t['id']);
                $links = array_map(function ($a) {
                    $name = htmlspecialchars($a['name'], ENT_QUOTES);
                    return "<a href=\"/artists/{$a['id']}\" class=\"track-card__artist-link\">{$name}</a>";
                }, $artists);
                echo implode(', ', $links);
                ?>
            </p>
            <p>
                Жанри:
                <?= implode(
                    ', ',
                    array_map(
                        fn($g) => htmlspecialchars($g['name'], ENT_QUOTES),
                        (new \App\Models\Track())->getGenres($t['id'])
                    )
                ) ?>
            </p>
        </div>
    <?php endforeach; ?>
</section>
<?php if ($totalPages > 1): ?>
    <nav class="pagination">
        <?php if ($page > 1): ?>
            <a href="?q=<?= urlencode($q) ?>&genre=<?= $selectedGenre ?>&page=<?= $page - 1 ?>" class="pagination__link">
                Попередня</a>
        <?php endif; ?>

        <?php for ($p = 1; $p <= $totalPages; $p++): ?>
            <a href="?q=<?= urlencode($q) ?>&genre=<?= $selectedGenre ?>&page=<?= $p ?>"
                class="pagination__link <?= $p === $page ? 'is-active' : '' ?>"><?= $p ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?q=<?= urlencode($q) ?>&genre=<?= $selectedGenre ?>&page=<?= $page + 1 ?>"
                class="pagination__link">Наступна
            </a>
        <?php endif; ?>
    </nav>
<?php endif; ?>