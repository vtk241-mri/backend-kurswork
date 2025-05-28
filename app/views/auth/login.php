<section class="auth-section auth-section--login">
    <h1 class="title title--section">Увійти</h1>

    <?php if (!empty($error)): ?>
        <p class="auth-error"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
    <?php endif; ?>

    <form action="/login" method="POST" class="auth-form">
        <div class="form-group">
            <label for="email">Електронна пошта</label>
            <input type="email" id="email" name="email" class="form-control" required
                value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>">
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="button auth-form__submit">Увійти</button>
    </form>

    <p class="auth-switch">
        Ще немаєте акаунт? <a href="/register">Зареєструватися</a>
    </p>
</section>