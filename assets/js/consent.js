(function () {
  'use strict';

  var COOKIE = 'clicki_consent';
  var bar = document.querySelector('[data-cookie-bar]');

  function read() {
    var m = document.cookie.match(new RegExp('(?:^|; )' + COOKIE + '=([^;]*)'));
    return m ? decodeURIComponent(m[1]) : null;
  }

  function write(value) {
    var secure = location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = COOKIE + '=' + encodeURIComponent(value) + '; Max-Age=' + (60 * 60 * 24 * 365) + '; Path=/; SameSite=Lax' + secure;
  }

  function show() {
    if (!bar) return;
    bar.hidden = false;
    requestAnimationFrame(function () { bar.classList.add('is-visible'); });
  }

  function hide() {
    if (!bar) return;
    bar.classList.remove('is-visible');
    setTimeout(function () { bar.hidden = true; }, 350);
  }

  // Voľba je dostupná pre prípadné budúce skripty (štatistika, marketing): window.clickiConsent.
  window.clickiConsent = {
    value: read(),
    allows: function (category) {
      if (category === 'necessary') return true;
      return window.clickiConsent.value === 'all';
    }
  };

  if (!read()) show();

  document.addEventListener('click', function (e) {
    var choice = e.target.closest && e.target.closest('[data-cookie-choice]');
    if (choice) {
      var v = choice.getAttribute('data-cookie-choice');
      write(v);
      window.clickiConsent.value = v;
      hide();
      return;
    }
    if (e.target.closest && e.target.closest('[data-cookie-settings]')) show();
  });
})();
