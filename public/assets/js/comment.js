document.addEventListener("DOMContentLoaded", () => {
  const sec = document.getElementById("comments-section");
  if (!sec) return;

  const trackId = sec.dataset.trackId;
  const list = document.getElementById("comments-list");
  const commentForm = document.getElementById("comment-form");

  function renderComments(arr) {
    if (!arr.length) {
      list.innerHTML = "<p>Поки що нема коментарів.</p>";
      return;
    }
    list.innerHTML = arr
      .map(
        (c) => `
      <div class="comment">
        <p><strong>${c.user_name}</strong> <em>${new Date(
          c.created_at
        ).toLocaleString()}</em></p>
        <p>${c.content}</p>
      </div>
    `
      )
      .join("");
  }

  fetch(`/api/comments/${trackId}`)
    .then((r) => r.json())
    .then(renderComments)
    .catch(() => (list.innerHTML = "<p>Не вдалося завантажити коментарі.</p>"));

  if (commentForm) {
    commentForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const fd = new FormData(commentForm);
      fetch(`/api/comments/${trackId}`, {
        method: "POST",
        body: fd,
      })
        .then((r) => {
          if (!r.ok) throw new Error();
          return r.json();
        })
        .then(renderComments)
        .catch(() => alert("Не вдалося додати коментар."))
        .finally(() => commentForm.reset());
    });
  }
});
