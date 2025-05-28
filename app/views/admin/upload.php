<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<h1>Завантажити новий трек</h1>

<form action="/tracks/upload" method="POST" enctype="multipart/form-data">
    <label for="title">Назва треку:</label>
    <input type="text" name="title" required>

    <label for="file">Файл треку:</label>
    <input type="file" name="file" required>

    <label for="artist_id">Артист:</label>
    <select name="artist_id" required>
        <?php foreach ($artists as $artist): ?>
            <option value="<?= htmlspecialchars($artist['id'], ENT_QUOTES) ?>">
                <?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="genre_id">Жанр:</label>
    <select name="genre_id" required>
        <?php foreach ($genres as $genre): ?>
            <option value="<?= htmlspecialchars($genre['id'], ENT_QUOTES) ?>">
                <?= htmlspecialchars($genre['name'], ENT_QUOTES) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Завантажити</button>
</form>