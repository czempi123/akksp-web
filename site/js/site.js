/* AKKSP — interakce webu (bez knihoven, ~2 kB). V WordPressu se načte jako jeden skript v child tématu. */
(() => {
  "use strict";
  // ?static = bez animací (pro tisk / snímky obrazovky)
  const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches || /[?&]static\b/.test(location.search);
  if (reduce) document.documentElement.classList.remove("js");

  /* Navigace: po odscrollování zpevní sklo */
  const nav = document.querySelector("[data-nav]");
  if (nav) {
    const onScroll = () => nav.classList.toggle("is-scrolled", window.scrollY > 24);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
  }

  /* Mobilní menu: tři čáry → × */
  const burger = document.querySelector("[data-burger]");
  const panel = document.querySelector("[data-mobile]");
  if (burger && panel) {
    const setOpen = (open) => {
      burger.setAttribute("aria-expanded", String(open));
      panel.classList.toggle("is-open", open);
      document.body.style.overflow = open ? "hidden" : "";
    };
    burger.addEventListener("click", () => setOpen(burger.getAttribute("aria-expanded") !== "true"));
    panel.addEventListener("click", (e) => { if (e.target.closest("a")) setOpen(false); });
    document.addEventListener("keydown", (e) => { if (e.key === "Escape") setOpen(false); });
  }

  /* Oblasti: rozbalení s „+ → ×" */
  document.querySelectorAll(".ak-area__btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      const item = btn.parentElement;
      const open = !item.classList.contains("is-open");
      item.classList.toggle("is-open", open);
      btn.setAttribute("aria-expanded", String(open));
    });
  });

  /* Hero: přepínač CZ / PL (v WordPressu přepne na jazykovou verzi přes Polylang) */
  const hero = document.querySelector("[data-hero]");
  const seg = hero && hero.querySelector(".ak-seg");
  if (seg) {
    seg.addEventListener("click", (e) => {
      const b = e.target.closest("button[data-set]");
      if (!b || seg.dataset.lang === b.dataset.set) return;
      const lang = b.dataset.set;
      seg.dataset.lang = lang;
      seg.querySelectorAll("button").forEach((x) => x.setAttribute("aria-pressed", String(x === b)));
      const swaps = hero.querySelectorAll(".ak-swap");
      swaps.forEach((s) => s.classList.add("is-out"));
      setTimeout(() => {
        swaps.forEach((s) => { s.innerHTML = s.dataset[lang]; s.setAttribute("lang", lang === "pl" ? "pl" : "cs"); s.classList.remove("is-out"); });
      }, reduce ? 0 : 250);
    });
  }

  /* Odkrytí sekcí — jen jednou */
  const items = document.querySelectorAll(".ak-reveal, .ak-mask");
  if (reduce || !("IntersectionObserver" in window)) {
    items.forEach((el) => el.classList.add("is-in"));
  } else {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (!en.isIntersecting) return;
        en.target.classList.add("is-in");
        io.unobserve(en.target);
      });
    }, { rootMargin: "0px 0px -10% 0px" });
    items.forEach((el) => io.observe(el));
  }

  const y = document.querySelector("[data-year]");
  if (y) y.textContent = new Date().getFullYear();
})();
