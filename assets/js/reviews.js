/* Slider recenzií: šípky, pomalé automatické posúvanie (pozastaví sa pri myši / mimo obrazovky). */
(function () {
  'use strict';

  var root = document.querySelector('[data-reviews]');
  if (!root) return;
  var track = root.querySelector('[data-reviews-track]');
  var prev = root.querySelector('[data-reviews-prev]');
  var next = root.querySelector('[data-reviews-next]');
  var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ľavý okraj obsahu -> prvá karta lícuje s nadpisom, zvyšok ide až k okraju okna
  function setEdge() {
    var box = root.querySelector('.container');
    if (!box) return;
    var wrap = track.parentElement.getBoundingClientRect();
    var left = Math.max(0, wrap.left);
    var right = Math.max(0, document.documentElement.clientWidth - wrap.right);
    track.style.setProperty('--edge', Math.min(left, right) + 'px');
  }
  setEdge();
  window.addEventListener('resize', setEdge);

  function stepSize() {
    var card = track.querySelector('.review');
    if (!card) return track.clientWidth;
    return card.getBoundingClientRect().width + parseFloat(getComputedStyle(track).columnGap || 0);
  }
  function atEnd() { return track.scrollLeft + track.clientWidth >= track.scrollWidth - 4; }
  function go(dir) {
    if (dir > 0 && atEnd()) { track.scrollTo({ left: 0, behavior: 'smooth' }); return; }
    if (dir < 0 && track.scrollLeft <= 4) { track.scrollTo({ left: track.scrollWidth, behavior: 'smooth' }); return; }
    track.scrollBy({ left: dir * stepSize(), behavior: 'smooth' });
  }

  prev.addEventListener('click', function () { go(-1); });
  next.addEventListener('click', function () { go(1); });
  track.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight') { e.preventDefault(); go(1); }
    if (e.key === 'ArrowLeft') { e.preventDefault(); go(-1); }
  });

  if (reduceMotion) return;
  var paused = false, onScreen = false;
  root.addEventListener('pointerenter', function (e) { if (e.pointerType === 'mouse') paused = true; });
  root.addEventListener('pointerleave', function (e) { if (e.pointerType === 'mouse') paused = false; });
  track.addEventListener('focusin', function () { paused = true; });
  track.addEventListener('focusout', function () { paused = false; });
  track.addEventListener('touchstart', function () { paused = true; }, { passive: true });
  track.addEventListener('touchend', function () { setTimeout(function () { paused = false; }, 4000); }, { passive: true });
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (e) { onScreen = e[0].isIntersecting; }).observe(root);
  }
  setInterval(function () { if (!paused && onScreen && !document.hidden) go(1); }, 5200);
})();
