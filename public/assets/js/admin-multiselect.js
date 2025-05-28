document.addEventListener("DOMContentLoaded", () => {
  function initTomSelect(selector, apiUrl) {
    const el = document.querySelector(selector);
    if (!el) return;

    new TomSelect(el, {
      valueField: "id",
      labelField: "name",
      searchField: "name",
      maxOptions: 50,
      plugins: ["remove_button"],
      loadThrottle: 200,
      load(query, callback) {
        if (!query.length) return callback();
        fetch(`${apiUrl}?q=${encodeURIComponent(query)}`)
          .then((res) => res.json())
          .then((json) => callback(json))
          .catch(() => callback());
      },
      create: false,
    });
  }

  initTomSelect("#artist_select", "/api/artists");
  initTomSelect("#genre_select", "/api/genres");
});
