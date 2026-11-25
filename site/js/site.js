/* AKKSP — interakce webu (bez knihoven).
   Funguje samostatně (prototyp) i ve WordPressu / editoru Elementoru. */
(() => {
  "use strict";
  // ?static = bez animací (pro tisk / snímky obrazovky)
  const reduce = window.matchMedia("(prefers-reduced-motion: reduce)").matches || /[?&]static\b/.test(location.search);
  const inEditor = () => document.body.classList.contains("elementor-editor-active");
  if (reduce) document.documentElement.classList.remove("js");

  /* --- Globální části (jednou na stránku) ------------------------------ */
  function initGlobal() {
    const nav = document.querySelector("[data-nav]");
    if (nav) {
      const onScroll = () => nav.classList.toggle("is-scrolled", window.scrollY > 24);
      onScroll();
      window.addEventListener("scroll", onScroll, { passive: true });
    }

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

    const y = document.querySelector("[data-year]");
    if (y) y.textContent = new Date().getFullYear();
  }

  /* --- Části uvnitř obsahu (lze volat opakovaně pro každý widget) ------ */
  function initScope(root) {
    // Oblasti: rozbalení „+ → ×"
    root.querySelectorAll(".ak-area__btn:not([data-ak-ready])").forEach((btn) => {
      btn.dataset.akReady = "1";
      btn.addEventListener("click", () => {
        const item = btn.parentElement;
        const open = !item.classList.contains("is-open");
        item.classList.toggle("is-open", open);
        btn.setAttribute("aria-expanded", String(open));
      });
    });

    // Hero: přepínač CZ / PL v rámci stránky (pokud nejsou odkazy na jazykové verze)
    root.querySelectorAll("[data-hero]:not([data-ak-ready])").forEach((hero) => {
      hero.dataset.akReady = "1";
      const seg = hero.querySelector(".ak-seg");
      if (!seg) return;
      seg.addEventListener("click", (e) => {
        const b = e.target.closest("button[data-set]");
        if (!b || seg.dataset.lang === b.dataset.set) return;
        const lang = b.dataset.set;
        seg.dataset.lang = lang;
        seg.querySelectorAll("button").forEach((x) => x.setAttribute("aria-pressed", String(x === b)));
        const swaps = hero.querySelectorAll(".ak-swap");
        swaps.forEach((s) => s.classList.add("is-out"));
        setTimeout(() => {
          swaps.forEach((s) => {
            if (s.dataset[lang] !== undefined) s.innerHTML = s.dataset[lang];
            s.setAttribute("lang", lang === "pl" ? "pl" : "cs");
            s.classList.remove("is-out");
          });
        }, reduce ? 0 : 250);
      });
    });

    // Odkrytí — jen jednou; v editoru vše hned viditelné
    const items = root.querySelectorAll(".ak-reveal:not(.is-in), .ak-mask:not(.is-in)");
    if (reduce || inEditor() || !("IntersectionObserver" in window)) {
      items.forEach((el) => el.classList.add("is-in"));
      return;
    }
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (!en.isIntersecting) return;
        en.target.classList.add("is-in");
        io.unobserve(en.target);
      });
    }, { rootMargin: "0px 0px -10% 0px" });
    items.forEach((el) => io.observe(el));
  }

  const start = () => { initGlobal(); initScope(document); };
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", start);
  else start();

  // Elementor: po vykreslení / úpravě widgetu v editoru znovu inicializovat
  window.addEventListener("elementor/frontend/init", () => {
    if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;
    window.elementorFrontend.hooks.addAction("frontend/element_ready/global", ($scope) => {
      const el = $scope && $scope[0] ? $scope[0] : null;
      if (el) initScope(el);
    });
  });
})();
