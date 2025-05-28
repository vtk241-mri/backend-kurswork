<section class="genre-detail">
    <h1 class="title title--section">
        Жанр: <?= htmlspecialchars($genre['name'], ENT_QUOTES) ?>
    </h1>

    <?php if (empty($tracks)): ?>
        <p>У цьому жанрі ще немає треків.</p>
    <?php else: ?>
        <div class="tracks-list">
            <?php foreach ($tracks as $t): ?>
                <div class="track-card">
                    <?php if (!empty($t['cover_image'])): ?>
                        <a href="/tracks/<?= $t['id'] ?>">
                            <img src="<?= htmlspecialchars($t['cover_image'], ENT_QUOTES) ?>"
                                alt="<?= htmlspecialchars($t['title'], ENT_QUOTES) ?>" class="track-card__cover">
                        </a>
                    <?php endif; ?>

                    <h2 class="track-card__title">
                        <a href="/tracks/<?= $t['id'] ?>">
                            <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>
                        </a>
                    </h2>

                    <p class="track-card__genres">
                        <strong>Жанри:</strong>
                        <?php
                        $genres = (new \App\Models\Track())->getGenres((int) $t['id']);
                        echo implode(', ', array_map(
                            fn($g) => htmlspecialchars($g['name'], ENT_QUOTES),
                            $genres
                        ));
                        ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>