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

    // Přepínač CZ / PL: jezdec nejdřív plynule dojede, pak se načte jazyková mutace
    document.querySelectorAll(".ak-seg").forEach((seg) => {
      seg.addEventListener("click", (e) => {
        const a = e.target.closest("a[href]");
        if (!a || a.hasAttribute("aria-current") || e.metaKey || e.ctrlKey || e.shiftKey) return;
        e.preventDefault();
        seg.style.setProperty("--pos", [...seg.querySelectorAll("a")].indexOf(a));
        setTimeout(() => { window.location.href = a.href; }, reduce ? 0 : 260);
      });
    });

    initSearch();
  }

  /* --- Vyhledávání: overlay (v WordPressu uvnitř formulář Ivory Search) -- */
  function initSearch() {
    const overlay = document.querySelector("[data-search]");
    if (!overlay) return;
    const openers = document.querySelectorAll("[data-search-open]");
    let lastFocus = null;
    const input = () => overlay.querySelector('input[type="search"]');
    const setOpen = (open) => {
      overlay.classList.toggle("is-open", open);
      openers.forEach((b) => b.setAttribute("aria-expanded", String(open)));
      document.body.style.overflow = open ? "hidden" : "";
      if (open) {
        lastFocus = document.activeElement;
        setTimeout(() => { const i = input(); if (i) i.focus(); }, 60);
      } else if (lastFocus) {
        lastFocus.focus();
      }
    };
    openers.forEach((b) => b.addEventListener("click", () => setOpen(true)));
    if (location.hash.startsWith("#hledat")) setOpen(true); // přímý odkaz na vyhledávání
    overlay.querySelectorAll("[data-search-close]").forEach((b) => b.addEventListener("click", () => setOpen(false)));
    overlay.addEventListener("click", (e) => { if (e.target === overlay) setOpen(false); });
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && overlay.classList.contains("is-open")) setOpen(false);
      if (e.key === "/" && !overlay.classList.contains("is-open") && !/input|textarea/i.test(document.activeElement.tagName)) { e.preventDefault(); setOpen(true); }
    });

    // Ukázkové výsledky jen v prototypu (formulář s data-search-form). Na webu je nahradí Ivory Search.
    const form = overlay.querySelector("[data-search-form]");
    const box = overlay.querySelector("[data-search-results]");
    if (!form || !box) return;
    const demo = [
      ["Jak efektivně vymáhat pohledávky v Polsku?", "Článek", "https://aktrinec.cz/jak-efektivne-vymahat-pohledavky-v-polsku/"],
      ["Polská Sp. z o.o. v korporátní správě: Průvodce pro české podnikatele a investory", "Článek", "https://aktrinec.cz/polska-sp-z-o-o-v-korporatni-sprave-pruvodce-pro-ceske-podnikatele-a-investory/"],
      ["Elektronický podpis a ověřený profil v Polsku", "Článek", "https://aktrinec.cz/elektronicky-podpis-a-overeny-profil-v-polsku/"],
      ["Generační nástupnictví nezačíná smlouvou. Začíná rozhodnutím vlastníka", "Článek", "https://aktrinec.cz/planovani-nastupnictvi-ve-firme/"],
      ["Předání firmy nové generaci: Jak úspěšně naplánovat nástupnictví", "Článek", "https://aktrinec.cz/predani-firmy-nove-generaci/"],
      ["Úvěr nebo zápůjčka? Moc to neřešte.", "Článek", "https://aktrinec.cz/uver-nebo-zapujcka-moc-to-nereste/"],
      ["Informace o zavedení PKD 2025", "Článek", "https://aktrinec.cz/informace-o-zavedeni-pkd-2025/"],
      ["Pomůže vám Mgr. Ondřej Kadlubiec", "Tým", "https://aktrinec.cz/nas-tym/mgr-ondrej-kadlubiec/"],
      ["Pomůže vám Mgr. Dorota Filipczyk", "Tým", "https://aktrinec.cz/nas-tym/mgr-dorota-filipczyk/"],
      ["Smlouvy a obchodní vztahy", "Oblast", "#oblasti"],
      ["Spory a vymáhání pohledávek", "Oblast", "#oblasti"],
      ["Přeshraniční podnikání (CZ / PL)", "Oblast", "#oblasti"],
    ];
    const norm = (s) => s.toLowerCase().normalize("NFD").replace(/[̀-ͯ]/g, "");
    const esc = (s) => s.replace(/[&<>"]/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]));
    const mark = (title, q) => {
      const i = norm(title).indexOf(norm(q));
      return i < 0 ? esc(title) : esc(title.slice(0, i)) + "<mark>" + esc(title.slice(i, i + q.length)) + "</mark>" + esc(title.slice(i + q.length));
    };
    const field = form.querySelector("[data-search-input]");
    const run = () => {
      const q = field.value.trim();
      if (q.length < 2) { box.hidden = true; box.innerHTML = ""; return; }
      const hits = demo.filter((d) => norm(d[0]).includes(norm(q)));
      box.hidden = false;
      box.innerHTML = hits.length
        ? hits.map((d) => `<a class="ak-search__result" href="${d[2]}"><span>${d[1]}</span><b>${mark(d[0], q)}</b></a>`).join("")
        : `<p class="is-ajax-search-no-result">Nic jsme nenašli. Zkuste jiné slovo.</p>`;
    };
    field.addEventListener("input", run);
    form.addEventListener("submit", (e) => { e.preventDefault(); run(); });
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
