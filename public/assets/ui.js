export function initUI(
  navSelector,
  overlaySelector,
  closeBtnSelector,
  socialSelector
) {
  const navLink = document.querySelector(navSelector);
  const overlay = document.querySelector(overlaySelector);
  const closeBtn = document.querySelector(closeBtnSelector);

  navLink?.addEventListener("click", (e) => {
    e.preventDefault();
    overlay.style.display = "block";
  });

  closeBtn?.addEventListener("click", () => {
    overlay.style.display = "none";
  });

  document.querySelectorAll(socialSelector).forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const url = btn.getAttribute("href");
      if (!url || url === "#") return;
      e.preventDefault();

      const width = 500;
      const height = 600;
      const left = window.screen.width / 2 - width / 2;
      const top = window.screen.height / 2 - height / 2;

      window.open(
        url,
        "_blank",
        `width=${width},height=${height},top=${top},left=${left},status=no,toolbar=no,menubar=no,location=no`
      );
      btn.classList.add("clicked");
      setTimeout(() => btn.classList.remove("clicked"), 400);
    });
  });
}
