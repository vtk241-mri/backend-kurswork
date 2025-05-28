function debounce(fn, delay) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("artist-search-input");
  const clearBtn = document.getElementById("artist-search-clear");
  const container = document.getElementById("artists-container");
  if (!input || !container) return;

  function renderArtist(a) {
    return `
      <div class="artist-card">
        ${
          a.image
            ? `<a href="/artists/${a.id}"><img
               src="${a.image}"
               alt="${a.name}"
               class="artist-card__img"
             ></a>`
            : ""
        }
        <h2 class="artist-card__name">
          <a href="/artists/${a.id}" class="artist-card__link">
            ${a.name}
          </a>
        </h2>
      </div>
    `;
  }

  const doSearch = debounce(() => {
    const q = input.value.trim();
    const url = q ? `/api/artists?q=${encodeURIComponent(q)}` : "/api/artists";
    fetch(url)
      .then((r) => r.json())
      .then((data) => {
        if (!Array.isArray(data)) return;
        container.innerHTML = data.length
          ? data.map(renderArtist).join("")
          : "<p>Нічого не знайдено.</p>";
      })
      .catch(() => {
        container.innerHTML = "<p>Помилка при пошуку.</p>";
      });
  }, 300);

  input.addEventListener("input", () => {
    doSearch();
  });

  clearBtn.addEventListener("click", () => {
    input.value = "";
    doSearch();
  });
});
