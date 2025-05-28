<section class="profile-section">
    <h1 class="title title--section">Профіль користувача</h1>

    <?php if (isset($user)): ?>
        <div class="profile-details">
            <?php if (!empty($user['avatar'])): ?>
                <div class="profile-avatar-wrapper">
                    <img src="<?= htmlspecialchars($user['avatar'], ENT_QUOTES) ?>"
                        alt=" Аватар <?= htmlspecialchars($user['username'], ENT_QUOTES) ?>" class="profile-avatar">
                </div>
            <?php endif; ?>
            <div class="profile-detail">
                <span class="profile-detail__label">Ім'я:</span>
                <span class="profile-detail__value">
                    <?= htmlspecialchars($user['username'] ?? $user['name'], ENT_QUOTES) ?>
                </span>
            </div>
            <div class="profile-detail">
                <span class="profile-detail__label">Email:</span>
                <span class="profile-detail__value">
                    <?= htmlspecialchars($user['email'], ENT_QUOTES) ?>
                </span>
            </div>
            <div class="profile-detail">
                <span class="profile-detail__label">Роль:</span>
                <span class="profile-detail__value">
                    <?= htmlspecialchars($user['role'], ENT_QUOTES) ?>
                </span>
            </div>
            <?php if (!empty($user['created_at'])): ?>
                <div class="profile-detail">
                    <span class="profile-detail__label">Зареєстрований:</span>
                    <span class="profile-detail__value">
                        <?= htmlspecialchars($user['created_at'], ENT_QUOTES) ?>
                    </span>
                </div>
            <?php endif; ?>
            <p>
                <a href="profile/edit" class="button profile__btn">Редагувати профіль</a>
            </p>
        </div>

    <?php else: ?>
        <p>Інформацію про користувача не знайдено.</p>
    <?php endif; ?>
</section>