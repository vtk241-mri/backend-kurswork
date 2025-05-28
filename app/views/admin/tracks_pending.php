<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--tracks-pending">
    <h1 class="pending__title title title--section">Очікують підтвердження</h1>

    <?php if (empty($pending)): ?>
        <p class="nothing">Немає нових треків.</p>
    <?php else: ?>
        <div class="pending-grid">
            <?php foreach ($pending as $t): ?>
                <div class="pending-card">
                    <?php if (!empty($t['cover_image'])): ?>
                        <div class="pending-card__img">
                            <img src="<?= htmlspecialchars($t['cover_image'], ENT_QUOTES) ?>"
                                alt="Cover <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>">
                        </div>
                    <?php endif; ?>

                    <div class="pending-card__body">
                        <h2 class="pending-card__title">
                            <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>
                        </h2>
                        <p class="pending-card__meta">
                            <span>Завантажив: <strong><?= htmlspecialchars($t['uploader'], ENT_QUOTES) ?></strong></span>
                            <span>Дата: <?= date('d.m.Y H:i', strtotime($t['created_at'])) ?></span>
                        </p>
                        <?php if (!empty($t['description'])): ?>
                            <p class="pending-card__desc">
                                <?= nl2br(htmlspecialchars(mb_strimwidth($t['description'], 0, 150, '…'), ENT_QUOTES)) ?>
                            </p>
                        <?php endif; ?>
                        <div class="pending-card__tags">
                            <?php
                            $artists = (new \App\Models\Track())->getArtists($t['id']);
                            $genres = (new \App\Models\Track())->getGenres($t['id']);
                            ?>
                            <?php if ($artists): ?>
                                <div class="tag-list">
                                    <strong>Виконавці:</strong>
                                    <?php foreach ($artists as $a): ?>
                                        <span class="tag"><?= htmlspecialchars($a['name'], ENT_QUOTES) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($genres): ?>
                                <div class="tag-list">
                                    <strong>Жанри:</strong>
                                    <?php foreach ($genres as $g): ?>
                                        <span class="tag"><?= htmlspecialchars($g['name'], ENT_QUOTES) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pending-card__actions">
                        <form action="/admin/tracks/<?= $t['id'] ?>/approve" method="POST">
                            <button type="submit" class="button pending-card__btn pending-card__btn--approve">
                                Схвалити
                            </button>
                        </form>
                        <form action="/admin/tracks/<?= $t['id'] ?>/reject" method="POST">
                            <button type="submit" class="button pending-card__btn pending-card__btn--reject">
                                Відхилити
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>