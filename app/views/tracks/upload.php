<section class="upload-section">
    <h1 class="title title--section">Завантажити новий трек</h1>

    <form action="/tracks/upload" method="POST" enctype="multipart/form-data" class="upload-form">
        <div class="form-group">
            <label for="title">Назва треку:</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="file">Файл треку (MP3):</label>
            <input type="file" id="file" name="file" class="form-control" accept="audio/*" required>
        </div>

        <div class="form-group">
            <label for="description">Опис треку</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label for="cover_image">Обкладинка (JPEG/PNG)</label>
            <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
        </div>

        <div class="form-group">
            <label for="artist_select">Виконавці</label>
            <select id="artist_select" name="artist_ids[]" multiple placeholder="Почніть вводити ім’я…"></select>
        </div>

        <div class="form-group">
            <label for="genre_select">Жанри</label>
            <select id="genre_select" name="genre_ids[]" multiple placeholder="Почніть вводити жанр…"></select>
        </div>

        <button type="submit" class="button upload-form__submit">
            Завантажити
        </button>
    </form>
</section>