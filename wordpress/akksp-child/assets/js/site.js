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
    initDrawer();
    initCookie();
  }

  /* --- Kontaktní panel: otevře každé „Kontakt" i odkaz na #kontakt ------- */
  function initDrawer() {
    const d = document.querySelector("[data-drawer]");
    if (!d) return;
    d.classList.add("is-js"); // bez JS funguje přes :target
    const panel = d.querySelector("[data-drawer-panel]") || d;
    const openSel = "[data-contact-open], a[href='#kontakt']";
    let last = null;
    const focusables = () => [...panel.querySelectorAll("a[href], button, input, textarea, select, [tabindex]:not([tabindex='-1'])")].filter((el) => !el.disabled && el.offsetParent !== null);
    const setOpen = (open) => {
      if (open === d.classList.contains("is-open")) return;
      d.classList.toggle("is-open", open);
      document.querySelectorAll("[data-contact-open]").forEach((b) => b.setAttribute("aria-expanded", String(open)));
      document.body.style.overflow = open ? "hidden" : "";
      if (open) {
        last = document.activeElement;
        setTimeout(() => panel.focus({ preventScroll: true }), 40);
      } else {
        if (location.hash === "#kontakt") history.replaceState(null, "", location.pathname + location.search);
        if (last && last.focus) last.focus({ preventScroll: true });
      }
    };
    document.addEventListener("click", (e) => {
      const opener = e.target.closest(openSel);
      if (opener && !d.contains(opener)) { e.preventDefault(); setOpen(true); return; }
      if (e.target.closest("[data-drawer-close]")) { e.preventDefault(); setOpen(false); }
    });
    document.addEventListener("keydown", (e) => {
      if (!d.classList.contains("is-open")) return;
      if (e.key === "Escape") { setOpen(false); return; }
      if (e.key !== "Tab") return;
      const f = focusables();
      if (!f.length) return;
      const first = f[0], lastEl = f[f.length - 1];
      if (e.shiftKey && (document.activeElement === first || document.activeElement === panel)) { e.preventDefault(); lastEl.focus(); }
      else if (!e.shiftKey && document.activeElement === lastEl) { e.preventDefault(); first.focus(); }
    });
    if (location.hash === "#kontakt") setOpen(true);

    // Ukázkový formulář (prototyp): kontrola polí přímo u pole, bez alertů
    const form = d.querySelector("[data-contact-form]");
    if (!form) return;
    const status = form.querySelector("[data-form-status]");
    const msg = (k) => form.dataset["msg" + k] || "";
    const setErr = (field, text) => {
      const box = field.closest(".ak-field");
      let err = box.querySelector(".ak-field__err");
      box.classList.toggle("is-error", !!text);
      field.setAttribute("aria-invalid", text ? "true" : "false");
      if (!text) { if (err) err.remove(); return; }
      if (!err) { err = document.createElement("span"); err.className = "ak-field__err"; err.id = field.name + "-err"; box.appendChild(err); field.setAttribute("aria-describedby", err.id); }
      err.textContent = text;
    };
    const check = (field) => {
      const v = field.value.trim();
      if (field.required && !v) return msg("Required");
      if (field.type === "email" && v && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v)) return msg("Email");
      return "";
    };
    form.querySelectorAll("input, textarea").forEach((f) => f.addEventListener("blur", () => { if (f.closest(".is-error") || f.value) setErr(f, check(f)); }));
    form.addEventListener("submit", (e) => {
      e.preventDefault();
      let firstBad = null;
      form.querySelectorAll("input, textarea").forEach((f) => { const t = check(f); setErr(f, t); if (t && !firstBad) firstBad = f; });
      if (firstBad) { status.textContent = ""; firstBad.focus(); return; }
      status.textContent = msg("Sent");
      form.reset();
    });
  }

  /* --- Cookie lišta (prototyp). Ve WordPressu ji vykresluje Complianz; téma jen dodá vzhled. */
  function initCookie() {
    const c = document.querySelector("[data-cookie]");
    if (!c) return;
    const KEY = "akksp-consent";
    const cats = [...c.querySelectorAll("[data-cookie-cat]")];
    const stored = () => { try { return JSON.parse(localStorage.getItem(KEY)); } catch (err) { return null; } };
    const close = () => c.classList.remove("is-open", "is-prefs");
    const open = (prefs) => {
      const s = stored();
      cats.forEach((x) => { x.checked = !!(s && s[x.name]); });
      c.classList.toggle("is-prefs", !!prefs);
      c.classList.add("is-open");
    };
    const save = (v) => { try { localStorage.setItem(KEY, JSON.stringify(Object.assign(v, { t: Date.now() }))); } catch (err) { /* soukromý režim */ } close(); };
    const all = (on) => Object.fromEntries(cats.map((x) => [x.name, on]));
    c.addEventListener("click", (e) => {
      if (e.target.closest("[data-cookie-accept]")) save(all(true));
      else if (e.target.closest("[data-cookie-deny]")) save(all(false));
      else if (e.target.closest("[data-cookie-prefs]")) c.classList.add("is-prefs");
      else if (e.target.closest("[data-cookie-save]")) save(Object.fromEntries(cats.map((x) => [x.name, x.checked])));
    });
    document.addEventListener("click", (e) => { if (e.target.closest("[data-cookie-open]")) { e.preventDefault(); open(true); } });
    const isStatic = /[?&]static\b/.test(location.search);
    if (location.hash === "#cookies") open(true);
    else if (!stored() && !isStatic) setTimeout(() => open(false), reduce ? 0 : 700);
  }

  /* --- Hero: fotky kanceláře se samy pomalu prolínají (bez ovládacích prvků).
     Stojí, když hero není vidět nebo je karta na pozadí; při omezeném pohybu jen první fotka. */
  function initSlides(root) {
    root.querySelectorAll("[data-slides]:not([data-ak-ready])").forEach((hero) => {
      hero.dataset.akReady = "1";
      const media = hero.querySelector("[data-slides-media]");
      const slides = media ? [...media.querySelectorAll(".ak-hero__slide")] : [];
      if (slides.length < 2 || reduce || inEditor()) return;
      media.classList.add("is-ready");
      slides.slice(1).forEach((img) => { img.loading = "eager"; });
      const ms = parseInt(getComputedStyle(hero).getPropertyValue("--ak-slide-ms"), 10) || 7000;
      let i = 0, timer = 0, visible = true;
      const next = () => {
        slides[i].classList.remove("is-active");
        i = (i + 1) % slides.length;
        slides[i].classList.add("is-active");
      };
      const run = () => {
        clearTimeout(timer);
        if (visible && !document.hidden) timer = setTimeout(() => { next(); run(); }, ms);
      };
      document.addEventListener("visibilitychange", run);
      if ("IntersectionObserver" in window) new IntersectionObserver((en) => { visible = en[0].isIntersecting; run(); }).observe(hero);
      run();
    });
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
      ["Firma: založení, stanovy, compliance", "Oblast", "#oblasti"],
      ["Obchod: smlouvy, akvizice, due diligence", "Oblast", "#oblasti"],
      ["Polsko: firma, obchod, majetek, spory", "Oblast", "#oblasti"],
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
    initSlides(root);
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
