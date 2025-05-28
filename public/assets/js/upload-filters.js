document.addEventListener("DOMContentLoaded", () => {
  function initFilter(inputId, listId) {
    const input = document.getElementById(inputId);
    const list = document.getElementById(listId);
    if (!input || !list) return;

    input.addEventListener("input", () => {
      const q = input.value.trim().toLowerCase();
      Array.from(list.querySelectorAll(".checkbox-label")).forEach((label) => {
        const text = label.textContent.trim().toLowerCase();
        label.style.display = text.includes(q) ? "" : "none";
      });
    });
  }

  initFilter("artist-filter", "artist-list");
  initFilter("genre-filter", "genre-list");
});
