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

  if (!fancy) return;

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
  document.querySelectorAll('.btn--primary:not(.btn--block), .btn--chrome, .scroll-top, .cmdk-trigger').forEach(function (btn) {
    btn.addEventListener('pointermove', function (e) {
      var r = btn.getBoundingClientRect();
      var dx = e.clientX - (r.left + r.width / 2);
      var dy = e.clientY - (r.top + r.height / 2);
      btn.style.transform = 'translate(' + dx * 0.22 + 'px,' + dy * 0.3 + 'px)';
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
})();
