<?php use Core\Auth; ?>

<section class="track-detail">
    <h1 class="track-detail__title"><?= htmlspecialchars($track['title'], ENT_QUOTES) ?></h1>

    <?php if (!empty($track['cover_image'])): ?>
        <div class="track-detail__cover">
            <img src="<?= htmlspecialchars($track['cover_image'], ENT_QUOTES) ?>"
                alt="<?= htmlspecialchars($track['title'], ENT_QUOTES) ?>">
        </div>
    <?php endif; ?>

    <?php if (!empty($track['description'])): ?>
        <p class="track-detail__desc">
            <?= nl2br(htmlspecialchars($track['description'], ENT_QUOTES)) ?>
        </p>
    <?php endif; ?>

    <div class="track-detail__player">
        <audio controls src="<?= htmlspecialchars($track['file_path'], ENT_QUOTES) ?>"></audio>
    </div>

    <ul class="track-detail__meta">
        <li>
            <strong>Виконавці:</strong>
            <?php
            echo implode(
                ', ',
                array_map(
                    fn($a) => "<a href=\"/artists/{$a['id']}\" class=\"track-detail__link\">" .
                    htmlspecialchars($a['name'], ENT_QUOTES) . '</a>',
                    $artists
                )
            );
            ?>
        </li>
        <li>
            <strong>Жанри:</strong>
            <?php
            echo implode(
                ', ',
                array_map(
                    fn($g) => "<a href=\"/genres/{$g['id']}\" class=\"track-detail__link\">" .
                    htmlspecialchars($g['name'], ENT_QUOTES) . '</a>',
                    $genres
                )
            );
            ?>
        </li>
    </ul>

    <section id="comments-section" class="comments-section" data-track-id="<?= $track['id'] ?>">
        <h2>Коментарі</h2>
        <div id="comments-list">Завантаження…</div>
        <?php if (Auth::check()): ?>
            <form id="comment-form" class="comment-form">
                <textarea name="content" class="comment-form__txt" placeholder="Ваш коментар…" required></textarea>
                <button type="submit" class="button comment-form__btn">Відправити</button>
            </form>
        <?php else: ?>
            <p>Увійдіть, щоб залишити коментар.</p>
        <?php endif; ?>
    </section>
</section>