(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var fancy = finePointer && !reduceMotion; // myšové efekty len na počítači a bez "obmedziť pohyb"

  /* =========================================================
     Rýchle vyhľadávanie — Ctrl/⌘ + K alebo "/"
     ========================================================= */
  var cmdk = document.querySelector('[data-cmdk]');
  var dataEl = document.getElementById('cmdk-data');
  if (cmdk && dataEl) {
    var data = JSON.parse(dataEl.textContent);
    var input = cmdk.querySelector('.cmdk__input');
    var list = cmdk.querySelector('.cmdk__list');
    var empty = cmdk.querySelector('.cmdk__empty');
    var isMac = /Mac|iPhone|iPad/.test(navigator.platform || navigator.userAgent);
    var results = [];
    var active = 0;
    var lastFocus = null;

    document.querySelectorAll('[data-cmdk-kbd]').forEach(function (k) { k.textContent = isMac ? '⌘K' : 'Ctrl K'; });

    var norm = function (s) {
      return (s || '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
    };
    data.items.forEach(function (it) { it._hay = norm(it.t + ' ' + (it.s || '') + ' ' + data.groups[it.g]); it._title = norm(it.t); });
    var groupOrder = ['actions', 'pages', 'services', 'projects'];

    function search(q) {
      var tokens = norm(q).split(/\s+/).filter(Boolean);
      var found = data.items.filter(function (it) {
        return tokens.every(function (tk) { return it._hay.indexOf(tk) !== -1; });
      });
      if (!tokens.length) {
        // bez dopytu: akcie, stránky, služby a pár najnovších projektov
        var shownProjects = 0;
        found = found.filter(function (it) { return it.g !== 'projects' || shownProjects++ < 6; });
      } else {
        found.sort(function (a, b) {
          var ga = groupOrder.indexOf(a.g), gb = groupOrder.indexOf(b.g);
          if (ga !== gb) return ga - gb;
          return (b._title.indexOf(tokens[0]) === 0) - (a._title.indexOf(tokens[0]) === 0);
        });
      }
      return found;
    }

    function render() {
      results = search(input.value);
      active = Math.min(active, Math.max(0, results.length - 1));
      list.innerHTML = '';
      empty.hidden = results.length > 0;
      var lastGroup = null;
      results.forEach(function (it, i) {
        if (it.g !== lastGroup) {
          var head = document.createElement('p');
          head.className = 'cmdk__group';
          head.textContent = data.groups[it.g];
          list.appendChild(head);
          lastGroup = it.g;
        }
        var a = document.createElement('a');
        a.className = 'cmdk__item' + (i === active ? ' is-active' : '');
        a.href = it.u;
        a.id = 'cmdk-opt-' + i;
        a.setAttribute('role', 'option');
        a.setAttribute('aria-selected', i === active ? 'true' : 'false');
        a.dataset.index = i;
        var media = document.createElement('span');
        media.className = 'cmdk__media';
        if (it.img) {
          var img = document.createElement('img');
          img.src = it.img; img.alt = ''; img.loading = 'lazy';
          media.appendChild(img);
        } else {
          media.innerHTML = data.icons[it.i] || '';
        }
        var text = document.createElement('span');
        text.className = 'cmdk__text';
        var t = document.createElement('strong'); t.textContent = it.t; text.appendChild(t);
        if (it.s) { var s = document.createElement('small'); s.textContent = it.s; text.appendChild(s); }
        var go = document.createElement('span');
        go.className = 'cmdk__go';
        go.textContent = '↵';
        a.appendChild(media); a.appendChild(text); a.appendChild(go);
        list.appendChild(a);
      });
      input.setAttribute('aria-activedescendant', results.length ? 'cmdk-opt-' + active : '');
    }

    function setActive(i) {
      if (!results.length) return;
      active = (i + results.length) % results.length;
      list.querySelectorAll('.cmdk__item').forEach(function (el) {
        var on = +el.dataset.index === active;
        el.classList.toggle('is-active', on);
        el.setAttribute('aria-selected', on ? 'true' : 'false');
        if (on) el.scrollIntoView({ block: 'nearest' });
      });
      input.setAttribute('aria-activedescendant', 'cmdk-opt-' + active);
    }

    function open() {
      if (!cmdk.hidden) return;
      lastFocus = document.activeElement;
      cmdk.hidden = false;
      document.documentElement.classList.add('cmdk-open');
      input.value = '';
      active = 0;
      render();
      input.focus(); // hneď, aby sa nestratili znaky napísané tesne po skratke
      requestAnimationFrame(function () { cmdk.classList.add('is-open'); });
    }
    function close() {
      if (cmdk.hidden) return;
      cmdk.classList.remove('is-open');
      document.documentElement.classList.remove('cmdk-open');
      cmdk.hidden = true;
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
    function go() {
      var it = results[active];
      if (!it) return;
      close();
      window.location.href = it.u;
    }

    document.querySelectorAll('[data-cmdk-open]').forEach(function (b) { b.addEventListener('click', open); });
    cmdk.querySelectorAll('[data-cmdk-close]').forEach(function (b) { b.addEventListener('click', close); });
    input.addEventListener('input', function () { active = 0; render(); });
    list.addEventListener('mousemove', function (e) {
      var item = e.target.closest('.cmdk__item');
      if (item && +item.dataset.index !== active) setActive(+item.dataset.index);
    });
    list.addEventListener('click', function (e) {
      if (e.target.closest('.cmdk__item')) close();
    });

    document.addEventListener('keydown', function (e) {
      var typing = /INPUT|TEXTAREA|SELECT/.test(document.activeElement.tagName) || document.activeElement.isContentEditable;
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        cmdk.hidden ? open() : close();
        return;
      }
      if (e.key === '/' && cmdk.hidden && !typing) {
        e.preventDefault();
        open();
        return;
      }
      if (cmdk.hidden) return;
      if (e.key === 'Escape') { e.preventDefault(); close(); }
      else if (e.key === 'ArrowDown') { e.preventDefault(); setActive(active + 1); }
      else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(active - 1); }
      else if (e.key === 'Enter') { e.preventDefault(); go(); }
      else if (e.key === 'Tab') { e.preventDefault(); input.focus(); }
    });
  }

  /* =========================================================
     Ukazovateľ prečítania stránky
     ========================================================= */
  var progress = document.querySelector('.scroll-progress span');
  if (progress) {
    var ticking = false;
    var updateProgress = function () {
      var max = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, window.scrollY / max) : 0) + ')';
      ticking = false;
    };
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(updateProgress); }
    }, { passive: true });
    updateProgress();
  }

  if (fancy) { // nasledujúce efekty sú len pre myš (bez "obmedziť pohyb")

  /* =========================================================
     Svetelné gule v hero reagujú na myš
     ========================================================= */
  var hero = document.querySelector('.hero');
  if (hero) {
    hero.addEventListener('pointermove', function (e) {
      var r = hero.getBoundingClientRect();
      hero.style.setProperty('--px', ((e.clientX - r.left) / r.width * 2 - 1).toFixed(3));
      hero.style.setProperty('--py', ((e.clientY - r.top) / r.height * 2 - 1).toFixed(3));
    });
  }

  /* =========================================================
     Žiara sledujúca kurzor na kartách
     ========================================================= */
  var spotSel = '.card, .principle, .project-card, .quote-step, .bento__services, .bento__story, .tier-card';
  document.querySelectorAll(spotSel).forEach(function (el) {
    if (el.querySelector(':scope > .fx-spot')) return;
    var s = document.createElement('span');
    s.className = 'fx-spot';
    s.setAttribute('aria-hidden', 'true');
    el.classList.add('has-spot');
    el.appendChild(s);
  });
  document.addEventListener('pointermove', function (e) {
    var el = e.target.closest && e.target.closest('.has-spot');
    if (!el) return;
    var r = el.getBoundingClientRect();
    el.style.setProperty('--mx', (e.clientX - r.left) + 'px');
    el.style.setProperty('--my', (e.clientY - r.top) + 'px');
  }, { passive: true });

  /* =========================================================
     Kurzor-kruh (natívny kurzor ostáva)
     ========================================================= */
  var ring = document.createElement('div');
  ring.className = 'fx-cursor';
  ring.setAttribute('aria-hidden', 'true');
  ring.innerHTML = '<span>' + ((dataEl && JSON.parse(dataEl.textContent).viewLabel) || 'View') + '</span>';
  document.body.appendChild(ring);
  var cx = -100, cy = -100, rx = -100, ry = -100;
  document.addEventListener('pointermove', function (e) {
    cx = e.clientX; cy = e.clientY;
    ring.classList.add('is-on');
    var t = e.target;
    var view = t.closest && t.closest('.project-card, .collage__item');
    var link = !view && t.closest && t.closest('a, button, label, select, [role="button"], input[type="range"]');
    ring.classList.toggle('is-view', !!view);
    ring.classList.toggle('is-link', !!link);
  }, { passive: true });
  document.addEventListener('pointerleave', function () { ring.classList.remove('is-on'); });
  document.addEventListener('pointerdown', function () { ring.classList.add('is-down'); });
  document.addEventListener('pointerup', function () { ring.classList.remove('is-down'); });
  (function loop() {
    rx += (cx - rx) * 0.2;
    ry += (cy - ry) * 0.2;
    ring.style.transform = 'translate3d(' + rx + 'px,' + ry + 'px,0)';
    requestAnimationFrame(loop);
  })();

  /* =========================================================
     Magnetické tlačidlá
     ========================================================= */
  document.querySelectorAll('.btn:not(.btn--block), .scroll-top, .cmdk-trigger').forEach(function (btn) {
    // dve tlačidlá vedľa seba sa nesmú prekrývať — tam je pohyb len symbolický
    var prev = btn.previousElementSibling, next = btn.nextElementSibling;
    var pair = btn.classList.contains('btn') &&
      ((prev && prev.classList.contains('btn')) || (next && next.classList.contains('btn')));
    var max = pair ? 3 : 14;
    function clamp(v) { return Math.max(-max, Math.min(max, v)); }
    btn.addEventListener('pointermove', function (e) {
      var r = btn.getBoundingClientRect();
      var dx = e.clientX - (r.left + r.width / 2);
      var dy = e.clientY - (r.top + r.height / 2);
      btn.style.transform = 'translate(' + clamp(dx * 0.22) + 'px,' + clamp(dy * 0.3) + 'px)';
    });
    btn.addEventListener('pointerleave', function () { btn.style.transform = ''; });
  });

  /* =========================================================
     3D náklon kariet projektov
     ========================================================= */
  document.querySelectorAll('.project-card').forEach(function (card) {
    card.addEventListener('pointermove', function (e) {
      var r = card.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width - 0.5;
      var py = (e.clientY - r.top) / r.height - 0.5;
      card.style.transform = 'perspective(900px) translateY(-6px) rotateX(' + (-py * 7).toFixed(2) + 'deg) rotateY(' + (px * 9).toFixed(2) + 'deg)';
    });
    card.addEventListener('pointerleave', function () { card.style.transform = ''; });
  });
  } // koniec efektov len pre myš

  /* =========================================================
     Nadpisy sekcií: slová sa odkrývajú zospodu
     ========================================================= */
  if (!reduceMotion && 'IntersectionObserver' in window) {
    var splitIo = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (en.isIntersecting) { en.target.classList.add('is-in'); splitIo.unobserve(en.target); }
      });
    }, { threshold: 0.4 });
    document.querySelectorAll('.section-head h2, .cta-band h2').forEach(function (h) {
      if (h.children.length) return; // nadpisy s vnútornými značkami nechávame na pokoji
      var text = h.textContent.trim();
      if (!text) return;
      h.setAttribute('aria-label', text);
      h.textContent = '';
      text.split(/\s+/).forEach(function (word, i, all) {
        var w = document.createElement('span');
        w.className = 'fx-w';
        w.setAttribute('aria-hidden', 'true');
        var inner = document.createElement('span');
        inner.textContent = word;
        inner.style.setProperty('--i', i);
        w.appendChild(inner);
        h.appendChild(w);
        if (i < all.length - 1) h.appendChild(document.createTextNode(' '));
      });
      h.classList.add('fx-split');
      splitIo.observe(h);
    });
  }

  /* =========================================================
     Karty projektov: paralaxa fotky pri scrolle + načítanie "živého náhľadu"
     ========================================================= */
  var projectCards = [].slice.call(document.querySelectorAll('.project-card'));
  projectCards.forEach(function (card) {
    var strip = card.querySelector('[data-strip]');
    if (!strip) return;
    var load = function () {
      strip.querySelectorAll('img[data-src]').forEach(function (img) { img.src = img.dataset.src; img.removeAttribute('data-src'); });
    };
    card.addEventListener('pointerenter', load, { once: true });
    card.addEventListener('focus', load, { once: true });
  });
  if (projectCards.length && !reduceMotion) {
    var parTick = false;
    var parUpdate = function () {
      parTick = false;
      var vh = window.innerHeight;
      projectCards.forEach(function (c) {
        var r = c.getBoundingClientRect();
        if (!r.height || r.bottom < 0 || r.top > vh) return;
        var p = ((r.top + r.height / 2) - vh / 2) / (vh / 2 + r.height / 2);
        c.style.setProperty('--par', (Math.max(-1, Math.min(1, p)) * -6).toFixed(2) + '%');
      });
    };
    var parQueue = function () { if (!parTick) { parTick = true; requestAnimationFrame(parUpdate); } };
    projectCards.forEach(function (c) { c.classList.add('has-parallax'); });
    window.addEventListener('scroll', parQueue, { passive: true });
    window.addEventListener('resize', parQueue);
    document.addEventListener('click', parQueue); // po prefiltrovaní portfólia
    parUpdate();
  }

  /* =========================================================
     Pás kľúčových slov: dva riadky idú pri scrolle opačne
     ========================================================= */
  var bands = [].slice.call(document.querySelectorAll('[data-kw-band]'));
  if (bands.length && !reduceMotion) {
    var bandTick = false;
    var bandUpdate = function () {
      bandTick = false;
      var vh = window.innerHeight;
      bands.forEach(function (band) {
        var r = band.getBoundingClientRect();
        if (r.bottom < -100 || r.top > vh + 100) return;
        var p = (r.top + r.height / 2 - vh / 2) / vh; // -1…1 podľa polohy v okne
        band.querySelectorAll('.kw-band__row').forEach(function (row) {
          row.style.setProperty('--kx', (p * -(+row.dataset.dir) * 360).toFixed(1) + 'px');
        });
      });
    };
    window.addEventListener('scroll', function () { if (!bandTick) { bandTick = true; requestAnimationFrame(bandUpdate); } }, { passive: true });
    window.addEventListener('resize', bandUpdate);
    bandUpdate();
  }

  /* =========================================================
     CTA video na pozadí: stojí mimo obrazovky a pri "obmedziť pohyb"
     ========================================================= */
  document.querySelectorAll('.cta-band__video, .page-hero__video').forEach(function (v) {
    if (reduceMotion) { v.pause(); v.removeAttribute('autoplay'); return; }
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (e) {
        if (e[0].isIntersecting) { var p = v.play(); if (p && p.catch) p.catch(function () {}); } else { v.pause(); }
      }).observe(v);
    }
  });

  /* =========================================================
     Služby: záložky "Čo zahŕňa / Čo dostanete" + jemný náklon vizuálu
     ========================================================= */
  document.querySelectorAll('[data-svc-tabs]').forEach(function (box) {
    var btns = [].slice.call(box.querySelectorAll('.svc-tabs__bar button'));
    var panels = [].slice.call(box.querySelectorAll('.svc-tabs__panel'));
    var holder = box.querySelector('.svc-tabs__panels');
    box.classList.add('is-js');
    // výška karty sleduje aktívny panel (bez prázdneho miesta pri kratšom zozname)
    function fit() {
      var act = box.querySelector('.svc-tabs__panel.is-active');
      var cs = getComputedStyle(holder);
      holder.style.height = (act.offsetHeight + parseFloat(cs.paddingTop) + parseFloat(cs.paddingBottom)) + 'px';
    }
    fit();
    window.addEventListener('resize', fit);
    window.addEventListener('load', fit);
    btns.forEach(function (b, i) {
      b.addEventListener('click', function () {
        btns.forEach(function (x, j) { x.classList.toggle('is-active', j === i); x.setAttribute('aria-selected', j === i ? 'true' : 'false'); });
        panels.forEach(function (p, j) { p.classList.toggle('is-active', j === i); });
        fit();
      });
    });
  });
  if (fancy) {
    document.querySelectorAll('.service-detail__visual').forEach(function (vis) {
      var sv = vis.querySelector('.sv');
      if (!sv) return;
      vis.addEventListener('pointermove', function (e) {
        var r = vis.getBoundingClientRect();
        sv.style.setProperty('--ry', (((e.clientX - r.left) / r.width - 0.5) * 6).toFixed(2) + 'deg');
        sv.style.setProperty('--rx', (-((e.clientY - r.top) / r.height - 0.5) * 5).toFixed(2) + 'deg');
      });
      vis.addEventListener('pointerleave', function () { sv.style.setProperty('--ry', '0deg'); sv.style.setProperty('--rx', '0deg'); });
    });
  }

  /* =========================================================
     Služby: navigácia zvýrazní sekciu, ktorá je práve na obrazovke
     ========================================================= */
  var svcNav = document.querySelector('[data-svc-nav]');
  if (svcNav && 'IntersectionObserver' in window) {
    var svcLinks = {};
    svcNav.querySelectorAll('[data-svc-link]').forEach(function (a) { svcLinks[a.dataset.svcLink] = a; });
    var svcSpy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting || !svcLinks[en.target.id]) return;
        Object.keys(svcLinks).forEach(function (k) { svcLinks[k].classList.toggle('is-active', k === en.target.id); });
        var a = svcLinks[en.target.id], box = svcNav.querySelector('.svc-nav__inner');
        box.scrollTo({ left: a.offsetLeft - box.clientWidth / 2 + a.offsetWidth / 2, behavior: 'smooth' });
      });
    }, { rootMargin: '-35% 0px -55% 0px' });
    document.querySelectorAll('.service-detail').forEach(function (s) { svcSpy.observe(s); });
  }

  /* =========================================================
     CTA blok: žiara plynule sleduje myš
     ========================================================= */
  document.querySelectorAll('.cta-band').forEach(function (band) {
    if (!fancy) return;
    var home = { x: 30, y: 20 }, tgt = { x: home.x, y: home.y }, cur = { x: home.x, y: home.y }, running = false;
    function loop() {
      cur.x += (tgt.x - cur.x) * 0.08; cur.y += (tgt.y - cur.y) * 0.08;
      band.style.setProperty('--cx', cur.x.toFixed(2) + '%');
      band.style.setProperty('--cy', cur.y.toFixed(2) + '%');
      if (Math.abs(tgt.x - cur.x) + Math.abs(tgt.y - cur.y) > 0.05) requestAnimationFrame(loop); else running = false;
    }
    function go() { if (!running) { running = true; requestAnimationFrame(loop); } }
    band.addEventListener('pointermove', function (e) {
      var r = band.getBoundingClientRect();
      // ::before je väčšie než blok (inset: -40 %), preto prepočet na jeho súradnice
      tgt.x = ((e.clientX - r.left) / r.width + 0.4) / 1.8 * 100;
      tgt.y = ((e.clientY - r.top) / r.height + 0.4) / 1.8 * 100;
      go();
    });
    band.addEventListener('pointerleave', function () { tgt.x = home.x; tgt.y = home.y; go(); });
  });

  /* =========================================================
     Hero pozadie: mriežka so svetelnými impulzmi (canvas)
     ========================================================= */
  (function () {
    var cv = document.querySelector('[data-hero-bg]');
    if (!cv || reduceMotion || !cv.getContext) return;
    var ctx = cv.getContext('2d');
    var W = 0, H = 0, cell = 64, pulses = [], raf = 0, onScreen = true;
    var mouse = { x: -999, y: -999 };

    function spawn() {
      var cols = Math.ceil(W / cell), rows = Math.ceil(H / cell);
      var horiz = Math.random() < 0.5, dir = Math.random() < 0.5 ? 1 : -1;
      return {
        x: Math.floor(Math.random() * (cols + 1)) * cell,
        y: Math.floor(Math.random() * (rows + 1)) * cell,
        dx: horiz ? dir : 0, dy: horiz ? 0 : dir,
        speed: 50 + Math.random() * 70, trav: 0,
        trail: [], max: 26 + Math.floor(Math.random() * 20)
      };
    }

    function resize() {
      var r = cv.getBoundingClientRect();
      var dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = r.width; H = r.height;
      if (!W || !H) return;
      cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      cell = W < 700 ? 44 : 64;
      var n = W < 700 ? 6 : 15;
      pulses = [];
      for (var i = 0; i < n; i++) pulses.push(spawn());
    }

    function frame(t, last) {
      // na mobile stačí ~30 fps
      if (W < 700 && last !== t && t - last < 30) {
        raf = requestAnimationFrame(function (n2) { frame(n2, last); });
        return;
      }
      var dt = Math.min((t - last) / 1000, 0.05);
      ctx.clearRect(0, 0, W, H);

      // mriežka
      ctx.lineWidth = 1;
      ctx.strokeStyle = 'rgba(255,255,255,0.045)';
      ctx.beginPath();
      for (var x = 0; x <= W; x += cell) { ctx.moveTo(x + 0.5, 0); ctx.lineTo(x + 0.5, H); }
      for (var y = 0; y <= H; y += cell) { ctx.moveTo(0, y + 0.5); ctx.lineTo(W, y + 0.5); }
      ctx.stroke();

      // uzly: všetky naraz jedným ťahom, rozsvietené pri kurzore zvlášť
      var hot = [];
      ctx.fillStyle = 'rgba(255,255,255,0.10)';
      ctx.beginPath();
      for (var gx = 0; gx <= W; gx += cell) {
        for (var gy = 0; gy <= H; gy += cell) {
          var d = Math.hypot(gx - mouse.x, gy - mouse.y);
          if (d < 190) { hot.push(gx, gy, 1 - d / 190); continue; }
          ctx.moveTo(gx + 1.9, gy + 0.5);
          ctx.arc(gx + 0.5, gy + 0.5, 1.4, 0, 6.2832);
        }
      }
      ctx.fill();
      for (var h = 0; h < hot.length; h += 3) {
        ctx.fillStyle = 'rgba(244,49,130,' + (0.18 + hot[h + 2] * 0.7).toFixed(3) + ')';
        ctx.beginPath();
        ctx.arc(hot[h] + 0.5, hot[h + 1] + 0.5, 1.4 + hot[h + 2] * 2.2, 0, 6.2832);
        ctx.fill();
      }

      // impulzy putujúce po mriežke (na križovatkách občas odbočia)
      ctx.lineCap = 'round';
      pulses.forEach(function (p, i) {
        var step = p.speed * dt;
        p.x += p.dx * step; p.y += p.dy * step; p.trav += step;
        if (p.trav >= cell) {
          p.trav -= cell;
          p.x = Math.round(p.x / cell) * cell; p.y = Math.round(p.y / cell) * cell;
          if (Math.random() < 0.35) { // odbočka o 90°
            var s = Math.random() < 0.5 ? 1 : -1, ox = p.dx;
            p.dx = -p.dy * s; p.dy = ox * s;
          }
        }
        p.trail.push(p.x, p.y);
        if (p.trail.length > p.max * 2) p.trail.splice(0, 2);
        if (p.x < -cell || p.x > W + cell || p.y < -cell || p.y > H + cell) { pulses[i] = spawn(); return; }

        var n = p.trail.length / 2, buckets = 6, per = Math.max(1, Math.ceil(n / buckets));
        for (var b = 0; b < buckets; b++) {
          var from = b * per, to = Math.min(n - 1, (b + 1) * per);
          if (from >= to) continue;
          ctx.strokeStyle = 'rgba(244,49,130,' + ((b + 1) / buckets * 0.85).toFixed(3) + ')';
          ctx.lineWidth = 1 + (b / buckets) * 1.2;
          ctx.beginPath();
          ctx.moveTo(p.trail[from * 2], p.trail[from * 2 + 1]);
          for (var j = from + 1; j <= to; j++) ctx.lineTo(p.trail[j * 2], p.trail[j * 2 + 1]);
          ctx.stroke();
        }
        ctx.fillStyle = 'rgba(244,49,130,0.22)';
        ctx.beginPath(); ctx.arc(p.x, p.y, 7, 0, 6.2832); ctx.fill();
        ctx.fillStyle = '#ff7ab6';
        ctx.beginPath(); ctx.arc(p.x, p.y, 2.4, 0, 6.2832); ctx.fill();
      });

      raf = onScreen && !document.hidden ? requestAnimationFrame(function (n2) { frame(n2, t); }) : 0;
    }

    function start() { if (!raf && W) raf = requestAnimationFrame(function (t) { frame(t, t); }); }

    resize();
    start();
    var rt;
    window.addEventListener('resize', function () { clearTimeout(rt); rt = setTimeout(resize, 150); });
    document.addEventListener('visibilitychange', start);
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (e) { onScreen = e[0].isIntersecting; if (onScreen) start(); }).observe(cv);
    }
    if (fancy) {
      document.addEventListener('pointermove', function (e) {
        var r = cv.getBoundingClientRect();
        mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
      }, { passive: true });
    }
  })();
})();
