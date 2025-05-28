function initMultiSelect(selector, apiUrl) {
  const el = document.querySelector(selector);
  if (!el || el.tomselect) return;

  new TomSelect(el, {
    valueField: "id",
    labelField: "name",
    searchField: "name",
    maxOptions: 50,
    loadThrottle: 200,
    load: function (query, callback) {
      if (!query.length) return callback();
      fetch(`${apiUrl}?q=${encodeURIComponent(query)}`)
        .then((res) => res.json())
        .then((json) => callback(json))
        .catch(() => callback());
    },
    create: false,
    placeholder: el.getAttribute("placeholder"),
    plugins: ["remove_button"],
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initMultiSelect("#artist_select", "/api/artists");
  initMultiSelect("#genre_select", "/api/genres");
});
