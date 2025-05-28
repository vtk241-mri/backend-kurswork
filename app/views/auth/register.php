<section class="auth-section auth-section--register">
    <h1 class="title title--section">Реєстрація</h1>

    <?php if (!empty($error)): ?>
        <p class="auth-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form action="/register" method="POST" class="auth-form">
        <div class="form-group">
            <label for="username">Ім'я користувача</label>
            <input type="text" id="username" name="username" class="form-control" required
                value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES) ?>">
        </div>

        <div class="form-group">
            <label for="email">Електронна пошта</label>
            <input type="email" id="email" name="email" class="form-control" required
                value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>">
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Підтвердження пароля</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                required>
        </div>

        <button type="submit" class="button auth-form__submit">Зареєструватися</button>
    </form>

    <p class="auth-switch">
        Вже є акаунт? <a href="/login">Увійти</a>
    </p>
</section>