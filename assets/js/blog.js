(function () {
  'use strict';

  /* ---------- ukazovateľ čítania ---------- */
  var bar = document.querySelector('.reading-progress span');
  var body = document.querySelector('[data-post-body]');
  if (bar && body) {
    var ticking = false;
    var update = function () {
      var r = body.getBoundingClientRect();
      var total = r.height - window.innerHeight * 0.5;
      var done = Math.min(1, Math.max(0, (window.innerHeight * 0.3 - r.top) / Math.max(total, 1)));
      bar.style.transform = 'scaleX(' + done.toFixed(4) + ')';
      ticking = false;
    };
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
    window.addEventListener('resize', update);
    update();
  }

  /* ---------- obsah článku: zvýrazní práve čítanú sekciu ---------- */
  var links = {};
  document.querySelectorAll('[data-toc-link]').forEach(function (a) { links[a.dataset.tocLink] = a; });
  var ids = Object.keys(links);
  if (ids.length && 'IntersectionObserver' in window) {
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        if (!en.isIntersecting) return;
        ids.forEach(function (k) { links[k].classList.toggle('is-active', k === en.target.id); });
      });
    }, { rootMargin: '-20% 0px -70% 0px' });
    ids.forEach(function (id) {
      var el = document.getElementById(id);
      if (el) spy.observe(el);
    });
  }

  /* ---------- kopírovanie odkazu ---------- */
  document.querySelectorAll('[data-copy-link]').forEach(function (btn) {
    var label = btn.textContent;
    btn.addEventListener('click', function () {
      var done = function () {
        btn.textContent = btn.dataset.copied || 'OK';
        setTimeout(function () { btn.textContent = label; }, 2000);
      };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(location.href).then(done, done);
      } else {
        var t = document.createElement('textarea');
        t.value = location.href;
        document.body.appendChild(t);
        t.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(t);
        done();
      }
    });
  });
})();
