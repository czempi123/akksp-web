/* AKKSP moodboard — interakce (bez knihoven) */
(() => {
  "use strict";

  const IMG = "../assets/aktrinec/";
  const arrow = '<svg class="ico btn-arrow"><use href="#i-arrow"/></svg>';
  const arrowSm = '<svg class="ico ico--sm btn-arrow"><use href="#i-arrow"/></svg>';
  const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  /* ------------------------------------------------------------------ data */
  // Oblasti = koncept; situace = sekce „Pomůžeme vám…" ze současného webu aktrinec.cz
  const services = [
    ["Smlouvy", "a obchodní vztahy", ["sepsat smlouvu", "vyjednat smlouvu", "když sjednáváte úvěr", "když poskytujete zápůjčku"]],
    ["Spory", "a vymáhání pohledávek", ["když vám zákazník neplatí", "bránit se žalobě", "v soudním řízení", "narovnat spor", "uzavřít smír"]],
    ["Duševní vlastnictví", "a ochrana know-how", ["přihlásit ochrannou známku", "přihlásit patent", "chránit průmyslové vlastnictví", "chránit duševní vlastnictví"]],
    ["Zaměstnanci", "a pracovní právo", ["ve sporu o neplatnost výpovědi", "v jednání s odborovou organizací", "odškodnit pracovní úraz", "proškolit zaměstnance"]],
    ["Korporátní agenda", "a restrukturalizace", ["předat rodinnou firmu dětem", "jednat s péčí řádného hospodáře", "zřídit nebo zrušit organizační složku"]],
    ["Regulace a compliance", "(AML, GDPR aj.)", ["s ochranou osobních údajů", "s vnitřními předpisy", "když k vám přijde kontrola"]],
    ["Nemovitosti", "a výstavba", ["převést nemovitost", "při prodeji nemovitosti", "zřídit věcné břemeno", "na katastru nemovitostí"]],
    ["Přeshraniční podnikání", "(CZ / PL)", ["podnikat v Polsku", "zřídit nebo zrušit organizační složku", "s právníkem, který mluví česky i polsky"]],
    ["M&A a strategie", "(akvizice, prodeje, fúze)", ["provést due diligence", "s fúzí", "když kupujete závod"]],
  ];

  const team = [
    ["Mgr. David Troszok", "Advokát", "2024-12-P6200389CHO-scaled-e1692876753442.jpg"],
    ["Mgr. Dorota Filipczyk", "Advokát", "2024-12-P6290544CHO-scaled-e1692876639242.jpg"],
    ["Mgr. Ondřej Kadlubiec", "Advokát", "2024-12-182-1.jpg"],
    ["Mgr. Michaela Kadlubcová Škutová", "Advokát", "2024-12-Kadlubcova.jpg"],
  ];

  const news = [
    ["Nástupce nestačí jmenovat: Jak oddělit vlastnictví firmy od jejího řízení", "Dobrý budoucí vlastník nemusí být nejlepším generálním ředitelem a schopný manažer nemusí být členem rodiny.", "2024-11-bryle.jpg", "Nástupnictví · díl 3"],
    ["Generační nástupnictví nezačíná smlouvou. Začíná rozhodnutím vlastníka", "Vlastník firmy obvykle dokáže přesně popsat zákazníky, výrobní procesy i finanční plán.", "2024-11-stolek.jpg", "Nástupnictví · díl 2"],
    ["Předání firmy nové generaci: Jak úspěšně naplánovat nástupnictví", "Předání firmy patří k nejdůležitějším rozhodnutím v životě podnikatele.", "2025-01-schody-3.jpg", "Nástupnictví · díl 1"],
  ];

  const pad = (n) => String(n).padStart(2, "0");
  const esc = (s) => s.replace(/&/g, "&amp;").replace(/</g, "&lt;");

  /* ------------------------------------------------------- render: oblasti */
  const svcRender = {
    a: (s, i) => `
      <div class="svc-a__item">
        <button type="button" class="svc-a__btn" aria-expanded="false" aria-controls="svc-a-${i}">
          <span class="svc-a__num">${pad(i + 1)}</span>
          <span><span class="svc-a__title">${esc(s[0])}</span> <span class="svc-a__sub">${esc(s[1])}</span></span>
          <span></span>
          <span class="px"><span class="px__ico"></span></span>
        </button>
        <div class="acc__panel" id="svc-a-${i}" role="region"><div><div class="svc-a__body"><p><b>Pomůžeme vám</b></p><ul>${s[2].map((x) => `<li>${esc(x)}</li>`).join("")}</ul></div></div></div>
      </div>`,
    b: (s, i) => `
      <div class="svc-b__item">
        <button type="button" class="svc-b__btn" aria-expanded="false" aria-controls="svc-b-${i}">
          <span class="svc-b__num">${pad(i + 1)} / 09</span>
          <span class="svc-b__title">${esc(s[0])}</span>
          <span class="svc-b__sub">${esc(s[1])}</span>
          <span class="px"><span class="px__ico"></span></span>
        </button>
        <div class="acc__panel" id="svc-b-${i}" role="region"><div><div class="svc-b__body"><p>Pomůžeme vám</p><ul>${s[2].map((x) => `<li>${esc(x)}</li>`).join("")}</ul></div></div></div>
      </div>`,
    c: (s, i) => `
      <div class="svc-c__item glass">
        <button type="button" class="svc-c__btn" aria-expanded="false" aria-controls="svc-c-${i}">
          <span class="svc-c__title">${esc(s[0])}</span>
          <span class="svc-c__sub">${esc(s[1])}</span>
          <span class="px"><span class="px__ico"></span></span>
        </button>
        <div class="acc__panel" id="svc-c-${i}" role="region"><div><div class="svc-c__body"><p>Pomůžeme vám</p><ul>${s[2].map((x) => `<li>${esc(x)}</li>`).join("")}</ul></div></div></div>
      </div>`,
  };
  // Pořadí jako v konceptu: sloupce shora dolů (1,4,7 / 2,5,8 / 3,6,9 → řádkový grid)
  const colOrder = [0, 3, 6, 1, 4, 7, 2, 5, 8];
  document.querySelectorAll("[data-services]").forEach((el) => {
    const v = el.dataset.services;
    const order = v === "a" ? [...Array(9).keys()] : colOrder;
    el.innerHTML = order.map((idx) => svcRender[v](services[idx], idx)).join("");
  });

  /* ------------------------------------------------------------ render: tým */
  const teamRender = {
    a: (p) => `<figure><img src="${IMG + p[2]}" alt="${esc(p[0])}" loading="lazy"><figcaption><b>${esc(p[0])}</b><span>${p[1]}</span></figcaption></figure>`,
    b: (p) => `<article class="team-b__card"><img src="${IMG + p[2]}" alt="${esc(p[0])}" loading="lazy"><div class="team-b__info"><small>${p[1]}</small><b>${esc(p[0])}</b><span class="team-b__go">Profil ${arrowSm}</span></div></article>`,
    c: (p) => `<article class="team-c__card"><img src="${IMG + p[2]}" alt="${esc(p[0])}" loading="lazy"><div class="team-c__plate"><b>${esc(p[0])}</b><span>${p[1]}</span></div></article>`,
  };
  document.querySelectorAll("[data-team]").forEach((el) => {
    el.insertAdjacentHTML("beforeend", team.map(teamRender[el.dataset.team]).join(""));
  });

  /* ------------------------------------------------------- render: aktuality */
  const newsRender = {
    a: (n) => `<article class="news-a__item"><img src="${IMG + n[2]}" alt="" loading="lazy"><small>${n[3]}</small><b>${esc(n[0])}</b><p>${esc(n[1])}</p><span class="btn-a btn-a--link" style="justify-self:start">Číst článek</span></article>`,
    b: (n) => `<article class="news-b__card"><img src="${IMG + n[2]}" alt="" loading="lazy"><div class="news-b__body"><small>Blog · ${n[3]}</small><b>${esc(n[0])}</b><span class="news-b__go">${arrow}</span></div></article>`,
    c: (n) => `<article class="news-c__card"><div class="news-c__media"><img src="${IMG + n[2]}" alt="" loading="lazy"><span>${n[3]}</span></div><b>${esc(n[0])}</b><p>${esc(n[1])}</p></article>`,
  };
  document.querySelectorAll("[data-news]").forEach((el) => {
    el.innerHTML = news.map(newsRender[el.dataset.news]).join("");
  });

  /* ------------------------------------------- přepínače + → × a accordion */
  document.addEventListener("click", (e) => {
    const t = e.target.closest("[data-toggle]");
    if (t) {
      t.setAttribute("aria-expanded", String(t.getAttribute("aria-expanded") !== "true"));
      return;
    }
    const acc = e.target.closest(".svc-a__btn, .svc-b__btn, .svc-c__btn");
    if (acc) {
      const item = acc.parentElement;
      const open = !item.classList.contains("is-open");
      item.classList.toggle("is-open", open);
      acc.setAttribute("aria-expanded", String(open));
    }
  });

  /* ---------------------------------------------------- hero C: CZ / PL */
  document.querySelectorAll("[data-hero-lang]").forEach((hero) => {
    const seg = hero.querySelector(".seg");
    seg.addEventListener("click", (e) => {
      const b = e.target.closest("button[data-set]");
      if (!b || seg.dataset.lang === b.dataset.set) return;
      const lang = b.dataset.set;
      seg.dataset.lang = lang;
      seg.querySelectorAll("button").forEach((x) => x.setAttribute("aria-pressed", String(x === b)));
      const swaps = hero.querySelectorAll(".swap");
      swaps.forEach((s) => s.classList.add("is-swapping"));
      setTimeout(() => {
        swaps.forEach((s) => {
          s.innerHTML = s.dataset[lang];
          s.classList.remove("is-swapping");
        });
        hero.setAttribute("lang", lang === "pl" ? "pl" : "cs");
      }, reduceMotion ? 0 : 260);
    });
  });

  /* -------------------------------------------------- pohyb: přehrát demo */
  document.querySelectorAll("[data-play]").forEach((btn) => {
    btn.addEventListener("click", () => {
      const stage = btn.parentElement.querySelector(".motion__stage");
      stage.classList.add("is-reset");
      void stage.offsetWidth; // reflow
      requestAnimationFrame(() => stage.classList.remove("is-reset"));
    });
  });

  /* ------------------------------------------------- kontrast WCAG 2.2 */
  const lum = (hex) => {
    const n = hex.replace("#", "");
    const c = [0, 2, 4].map((i) => parseInt(n.slice(i, i + 2), 16) / 255)
      .map((v) => (v <= 0.04045 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4));
    return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2];
  };
  const ratio = (a, b) => {
    const [l1, l2] = [lum(a), lum(b)].sort((x, y) => y - x);
    return (l1 + 0.05) / (l2 + 0.05);
  };
  document.querySelectorAll("[data-pairs]").forEach((box) => {
    box.innerHTML = box.dataset.pairs.split(";").map((p) => {
      const [fg, bg, label] = p.split("|");
      const r = ratio(fg, bg);
      const badge = r >= 4.5 ? ["ok", "AA text"] : r >= 3 ? ["lg", "AA velký"] : ["no", "jen dekor"];
      return `<div class="pal__check"><span class="sample" style="color:${fg};background:${bg}">Aa</span><span>${label}</span><span class="ratio">${r.toFixed(2)}:1</span><span class="badge badge--${badge[0]}">${badge[1]}</span></div>`;
    }).join("");
  });

  /* ------------------------------------------------------- výběr variant */
  const KEY = "akksp-moodboard-vyber-v1";
  const picker = document.querySelector(".picker");
  const list = picker.querySelector("[data-list]");
  const count = picker.querySelector("[data-count]");
  const status = picker.querySelector("[data-status]");
  const sections = [...document.querySelectorAll("[data-section]")];
  let state = {};
  try { state = JSON.parse(localStorage.getItem(KEY)) || {}; } catch (_) { state = {}; }

  const styleName = { A: "Editorial", B: "Institutional", C: "Glass & Soft" };
  const renderPicker = () => {
    let n = 0;
    list.innerHTML = sections.map((s) => {
      const name = s.dataset.section;
      const v = state[s.id];
      if (v) n++;
      return `<li class="${v ? "" : "is-empty"}"><span>${esc(name)}</span><span>${v || "—"}</span></li>`;
    }).join("");
    count.textContent = `${n} / ${sections.length}`;
  };
  sections.forEach((s) => {
    s.querySelectorAll(".variant__pick input").forEach((inp) => {
      if (state[s.id] === inp.value) inp.checked = true;
      inp.addEventListener("change", () => {
        state[s.id] = inp.value;
        localStorage.setItem(KEY, JSON.stringify(state));
        renderPicker();
        status.textContent = "";
      });
    });
  });
  renderPicker();

  picker.querySelector(".picker__toggle").addEventListener("click", () => {
    picker.dataset.collapsed = picker.dataset.collapsed === "true" ? "false" : "true";
  });
  picker.querySelector("[data-reset]").addEventListener("click", () => {
    state = {};
    localStorage.removeItem(KEY);
    document.querySelectorAll(".variant__pick input").forEach((i) => (i.checked = false));
    renderPicker();
    status.textContent = "Výběr vymazán.";
  });
  picker.querySelector("[data-copy]").addEventListener("click", async () => {
    const lines = sections.map((s, i) => {
      const v = state[s.id];
      return `${pad(i + 1)} ${s.dataset.section}: ${v ? `${v} (${styleName[v]})` : "nevybráno"}`;
    });
    const text = `AKKSP moodboard, výběr variant\n${lines.join("\n")}`;
    try {
      await navigator.clipboard.writeText(text);
      status.textContent = "Zkopírováno do schránky.";
    } catch (_) {
      status.textContent = "Kopírování selhalo. Označte seznam ručně.";
    }
  });

  /* --------------------------------------------------- reveal (jen jednou) */
  const reveals = document.querySelectorAll(".reveal");
  if (reduceMotion || !("IntersectionObserver" in window)) {
    reveals.forEach((r) => r.classList.add("is-in"));
  } else {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (en.isIntersecting) {
          en.target.classList.add("is-in");
          io.unobserve(en.target);
        }
      });
    }, { rootMargin: "0px 0px -8% 0px" });
    reveals.forEach((r) => io.observe(r));
  }
})();
