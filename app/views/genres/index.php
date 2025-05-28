<section class="genres-section">
    <h1 class="title title--section">Жанри</h1>

    <div class="genres-list">
        <?php foreach ($genres as $genre): ?>
            <a href="/genres/<?= $genre['id'] ?>" class="genre-card">
                <?= htmlspecialchars($genre['name'], ENT_QUOTES) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>