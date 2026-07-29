(function () {
  'use strict';

  /* ---------- category filter ---------- */
  var filterBar = document.querySelector('[data-filter-bar]');
  var grid = document.querySelector('[data-project-grid]');
  if (filterBar && grid) {
    var cards = grid.querySelectorAll('[data-category]');
    var emptyMsg = grid.querySelector('[data-empty-msg]');

    filterBar.addEventListener('click', function (e) {
      var btn = e.target.closest('.filter-btn');
      if (!btn) return;
      filterBar.querySelectorAll('.filter-btn').forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      var cat = btn.getAttribute('data-filter');
      var visibleCount = 0;
      cards.forEach(function (card) {
        var match = cat === 'all' || card.getAttribute('data-category') === cat;
        card.style.display = match ? '' : 'none';
        if (match) visibleCount++;
      });
      if (emptyMsg) emptyMsg.style.display = visibleCount === 0 ? '' : 'none';

      if (history.replaceState) {
        var url = new URL(window.location.href);
        if (cat === 'all') url.searchParams.delete('kategoria');
        else url.searchParams.set('kategoria', cat);
        history.replaceState(null, '', url);
      }
    });
  }

  /* ---------- lightbox ---------- */
  var lightbox = document.querySelector('[data-lightbox]');
  if (!lightbox) return;
  var img = lightbox.querySelector('img');
  var triggers = Array.prototype.slice.call(document.querySelectorAll('[data-lightbox-src]'));
  var current = -1;

  function open(index) {
    current = index;
    img.src = triggers[index].getAttribute('data-lightbox-src');
    img.alt = triggers[index].getAttribute('data-lightbox-alt') || '';
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
  }
  function close() {
    lightbox.classList.remove('is-open');
    document.body.style.overflow = '';
  }
  function step(dir) {
    if (!triggers.length) return;
    current = (current + dir + triggers.length) % triggers.length;
    open(current);
  }

  triggers.forEach(function (trigger, index) {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      open(index);
    });
  });

  var closeBtn = lightbox.querySelector('[data-lightbox-close]');
  if (closeBtn) closeBtn.addEventListener('click', close);
  var prevBtn = lightbox.querySelector('[data-lightbox-prev]');
  if (prevBtn) prevBtn.addEventListener('click', function () { step(-1); });
  var nextBtn = lightbox.querySelector('[data-lightbox-next]');
  if (nextBtn) nextBtn.addEventListener('click', function () { step(1); });

  lightbox.addEventListener('click', function (e) {
    if (e.target === lightbox) close();
  });
  document.addEventListener('keydown', function (e) {
    if (!lightbox.classList.contains('is-open')) return;
    if (e.key === 'Escape') close();
    if (e.key === 'ArrowRight') step(1);
    if (e.key === 'ArrowLeft') step(-1);
  });
})();
