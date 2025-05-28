function debounce(fn, delay) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("search-input");
  const clearBtn = document.getElementById("search-clear");
  const select = document.getElementById("genre-select");
  const container = document.getElementById("tracks-container");
  if (!input || !container || !select) return;

  function renderTrack(t) {
    return `
    <div class="track-card">
      ${
        t.cover_image
          ? `<a href="/tracks/${t.id}">
             <img src="${t.cover_image}" class="track-card__cover">
           </a>`
          : ""
      }
      <h2 class="track-card__title">
        <a href="/tracks/${t.id}">${t.title}</a>
      </h2>
      <p>Виконавці: ${t.artists}</p>
      <p>Жанри: ${t.genres}</p>
    </div>
  `;
  }

  const doSearch = debounce(() => {
    const q = input.value.trim();
    const genre = select.value;
    const params = new URLSearchParams();
    if (q) params.set("q", q);
    if (genre) params.set("genre", genre);

    fetch(`/api/search?${params.toString()}`)
      .then((r) => r.json())
      .then((data) => {
        container.innerHTML = data.length
          ? data.map(renderTrack).join("")
          : "<p>Нічого не знайдено.</p>";
      })
      .catch(() => {
        container.innerHTML = "<p>Помилка пошуку.</p>";
      });
  }, 300);

  input.addEventListener("input", (e) => {
    doSearch(e.target.value);
  });

  select.addEventListener("change", () => doSearch());

  clearBtn.addEventListener("click", () => {
    input.value = "";
    select.value = "0";
    doSearch("");
  });
});
