<section class="profile-section profile-section--edit">
    <h1 class="title title--section">Редагувати профіль</h1>

    <form action="/profile/update" method="POST" class="admin-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="username">Ім'я користувача</label>
            <input type="text" id="username" name="username" class="form-control" required
                value="<?= htmlspecialchars($user['username'] ?? $user['name'], ENT_QUOTES) ?>">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required
                value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>">
        </div>

        <div class="form-group">
            <label for="avatar">Аватар</label><br>
            <div class="profile-avatar-wrapper">

                <?php if (!empty($user['avatar'])): ?>
                    <img class="profile-avatar " src="<?= htmlspecialchars($user['avatar'], ENT_QUOTES) ?>" alt=""><br>
                <?php endif; ?>
            </div>
            <input type="file" name="avatar" id="avatar" accept="image/*">
        </div>


        <div class="form-group">
            <label for="password">Новий пароль <small>(залиште порожнім, якщо без змін)</small></label>
            <input type="password" id="password" name="password" class="form-control">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Підтвердження пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="button admin-btn">Зберегти зміни</button>
        <a href="/profile" class="button admin-btn" style="background-color: var(--color-muted)">Скасувати</a>
    </form>
</section>