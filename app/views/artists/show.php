<section class="artist-detail">
    <?php if (!empty($artist['image'])): ?>
        <div class="artist-detail__img-wrapper">
            <img src="<?= htmlspecialchars($artist['image'], ENT_QUOTES) ?>"
                alt="<?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>" class="artist-detail__img">
        </div>
    <?php endif; ?>

    <h1 class="title title--section">
        <?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>
    </h1>
    <?php if (!empty($artist['bio'])): ?>
        <p class="artist-detail__bio">
            <?= nl2br(htmlspecialchars($artist['bio'], ENT_QUOTES)) ?>
        </p>
    <?php endif; ?>

    <h2 class="title title--section">Треки виконавця</h2>
    <div class="tracks-list">
        <?php if (empty($tracks)): ?>
            <p>У цього виконавця ще немає треків.</p>
        <?php else: ?>
            <?php foreach ($tracks as $t): ?>
                <div class="track-card">
                    <?php if (!empty($t['cover_image'])): ?>
                        <a href="/tracks/<?= $t['id'] ?>">
                            <img src="<?= htmlspecialchars($t['cover_image'], ENT_QUOTES) ?>" class="track-card__cover"
                                alt="<?= htmlspecialchars($t['title'], ENT_QUOTES) ?>">
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
        <?php endif; ?>
    </div>
</section>