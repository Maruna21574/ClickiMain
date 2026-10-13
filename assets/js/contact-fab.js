(function () {
  'use strict';
  var fab = document.querySelector('[data-contact-fab]');
  if (!fab) return;
  var btn = fab.querySelector('.contact-fab__btn');
  var menu = fab.querySelector('.contact-fab__menu');

  function set(open) {
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    fab.classList.toggle('is-open', open);
    menu.hidden = !open;
  }
  btn.addEventListener('click', function () { set(btn.getAttribute('aria-expanded') !== 'true'); });
  document.addEventListener('click', function (e) { if (!fab.contains(e.target)) set(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') set(false); });
})();
