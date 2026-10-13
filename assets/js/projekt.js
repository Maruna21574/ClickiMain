/* Detail projektu: automatická "prechádzka" po webe v okne prehliadača, náklon pri myši
   a počítadlo pri swipe galérii na mobile. */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;

  /* ---------- prechádzka po webe ---------- */
  var strip = document.querySelector('[data-tour]');
  if (strip && strip.children.length > 1) {
    var n = strip.children.length;
    var dots = [].slice.call(document.querySelectorAll('[data-tour-dots] button'));
    var view = strip.parentElement;
    var i = 0, paused = false, onScreen = true;

    var show = function (k) {
      i = (k + n) % n;
      strip.style.setProperty('--i', i);
      dots.forEach(function (d, j) { d.classList.toggle('is-on', j === i); });
    };

    dots.forEach(function (d, j) {
      d.addEventListener('click', function () { show(j); });
    });

    if (!reduceMotion) {
      // myš: web sa plynule "scrolluje" celou prechádzkou (ako na kartách projektov), po odchode sa vráti hore
      var mockEl = view.closest('.pd-mock');
      view.addEventListener('pointerenter', function (e) {
        if (e.pointerType !== 'mouse') return;
        paused = true;
        mockEl.classList.add('is-scrolling');
      });
      view.addEventListener('pointerleave', function (e) {
        if (e.pointerType !== 'mouse') return;
        mockEl.classList.remove('is-scrolling');
        show(0);
        paused = false;
      });
      if ('IntersectionObserver' in window) {
        new IntersectionObserver(function (e) { onScreen = e[0].isIntersecting; }).observe(view);
      }
      setInterval(function () { if (!paused && onScreen && !document.hidden) show(i + 1); }, 3400);
    }
  }

  /* ---------- jemný 3D náklon okna prehliadača ---------- */
  var mock = document.querySelector('[data-tilt]');
  if (mock && finePointer && !reduceMotion) {
    mock.addEventListener('pointermove', function (e) {
      var r = mock.getBoundingClientRect();
      var px = (e.clientX - r.left) / r.width - 0.5;
      var py = (e.clientY - r.top) / r.height - 0.5;
      mock.style.setProperty('--ry', (px * 7).toFixed(2) + 'deg');
      mock.style.setProperty('--rx', (-py * 5).toFixed(2) + 'deg');
    });
    mock.addEventListener('pointerleave', function () {
      mock.style.setProperty('--ry', '0deg');
      mock.style.setProperty('--rx', '0deg');
    });
  }

  /* ---------- swipe galéria: "2 / 5" ---------- */
  var swipe = document.querySelector('[data-swipe]');
  var counter = document.querySelector('[data-swipe-count]');
  if (swipe && counter) {
    var total = +counter.dataset.total || swipe.children.length;
    var update = function () {
      // len keď je galéria skutočne posuvná (mobil); inak ostáva celkový počet
      if (swipe.scrollWidth <= swipe.clientWidth + 2) { counter.textContent = total; return; }
      var first = swipe.children[0];
      var step = first.getBoundingClientRect().width + parseFloat(getComputedStyle(swipe).columnGap || 0);
      var idx = Math.min(total, Math.round(swipe.scrollLeft / step) + 1);
      counter.textContent = idx + ' / ' + total;
    };
    swipe.addEventListener('scroll', function () { requestAnimationFrame(update); }, { passive: true });
    window.addEventListener('resize', update);
    update();
  }
})();
