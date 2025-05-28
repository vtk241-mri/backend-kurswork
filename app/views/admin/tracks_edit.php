<?php require_once __DIR__ . '/../layouts/admin_header.php'; ?>

<section class="admin-section admin-section--tracks-edit">
  <h1 class="title title--section">Редагувати трек</h1>

  <form
    action="/admin/tracks/<?= htmlspecialchars($track['id'], ENT_QUOTES) ?>/update"
    method="POST"
    enctype="multipart/form-data"
    class="admin-form"
  >
    <div class="form-group">
      <label for="title">Назва треку</label>
      <input
        type="text"
        id="title"
        name="title"
        class="form-control"
        required
        value="<?= htmlspecialchars($track['title'], ENT_QUOTES) ?>"
      >
    </div>

    <div class="form-group">
      <label for="artist_select">Виконавці</label>
      <select
        id="artist_select"
        name="artist_ids[]"
        multiple
        class="form-control"
      >
        <?php foreach ($artists as $artist): ?>
          <option
            value="<?= $artist['id'] ?>"
            <?= in_array($artist['id'], $selectedArtistIds) ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($artist['name'], ENT_QUOTES) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="genre_select">Жанри</label>
      <select
        id="genre_select"
        name="genre_ids[]"
        multiple
        class="form-control"
      >
        <?php foreach ($genres as $genre): ?>
          <option
            value="<?= $genre['id'] ?>"
            <?= in_array($genre['id'], $selectedGenreIds) ? 'selected' : '' ?>
          >
            <?= htmlspecialchars($genre['name'], ENT_QUOTES) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Поточна обкладинка</label><br>
      <?php if (!empty($track['cover_image'])): ?>
        <img
          src="<?= htmlspecialchars($track['cover_image'], ENT_QUOTES) ?>"
          alt="Cover <?= htmlspecialchars($track['title'], ENT_QUOTES) ?>"
          class="admin-thumb"
        >
      <?php else: ?>
        <p>Немає обкладинки</p>
      <?php endif; ?>
    </div>

    <div class="form-group">
      <label for="cover_image">Завантажити нову обкладинку</label>
      <input
        type="file"
        id="cover_image"
        name="cover_image"
        class="form-control"
        accept="image/*"
      >
    </div>

    <button type="submit" class="button admin-btn">Зберегти</button>
  </form>
</section>
