<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-dashboard">
    <h1 class="title title--section">Панель адміністратора</h1>

    <div class="dashboard-cards">
        <div class="dashboard-card">
            <h3>Треки</h3>
            <p class="dashboard-card__value"><?= $stats['tracks'] ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Користувачі</h3>
            <p class="dashboard-card__value"><?= $stats['users'] ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Коментарі</h3>
            <p class="dashboard-card__value"><?= $stats['new_comments'] ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Реєстрації</h3>
            <p class="dashboard-card__value"><?= $stats['new_regs'] ?></p>
        </div>
    </div>

    <div class="dashboard-lists">
        <div class="dashboard-list">
            <h4>Нещодавні треки</h4>
            <ul>
                <?php foreach ($recentTracks as $t): ?>
                    <li>
                        <a href="/admin/tracks/<?= $t['id'] ?>/edit">
                            <?= htmlspecialchars($t['title'], ENT_QUOTES) ?>
                        </a>
                        <span class="muted"><?= $t['created_at'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="dashboard-list">
            <h4>Нещодавні користувачі</h4>
            <ul>
                <?php foreach ($recentUsers as $u): ?>
                    <li>
                        <a href="/admin/users/<?= $u['id'] ?>/edit">
                            <?= htmlspecialchars($u['username'] ?? $u['name'], ENT_QUOTES) ?>
                        </a>
                        <span class="muted"><?= $u['created_at'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="dashboard-list">
            <h4>Нещодавні коментарі</h4>
            <ul>
                <?php foreach ($recentComments as $c): ?>
                    <li>
                        <p>
                            <strong><?= htmlspecialchars($c['user_name'], ENT_QUOTES) ?></strong>:
                            <?= nl2br(htmlspecialchars($c['content'], ENT_QUOTES)) ?>
                        </p>
                        <p>
                            До треку
                            <a href="/tracks/<?= htmlspecialchars($c['track_id'], ENT_QUOTES) ?>" rel="noopener">
                                <?= htmlspecialchars($c['track_title'], ENT_QUOTES) ?>
                            </a>
                            <span class="muted"><?= htmlspecialchars($c['created_at'], ENT_QUOTES) ?></span>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


    </div>
</section>