let documentActions = (e) => {
  const targetElement = e.target;
  if (targetElement.closest(".header__icon-menu")) {
    document.documentElement.classList.toggle("open-menu");
  }
};
document.addEventListener("click", documentActions);
