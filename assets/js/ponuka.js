(function () {
  'use strict';

  /* Konfigurátor dopytu na ponuka.php — zostavuje zoznam výberu rovnako ako quote_selection() v inc/functions.php */
  var form = document.getElementById('quote-form');
  var cfgEl = document.getElementById('quote-config');
  if (!form || !cfgEl) return;
  var cfg = JSON.parse(cfgEl.textContent);

  var itemsEl = form.querySelector('[data-items]');
  var pagesOut = form.querySelector('[data-pages-out]');
  var barType = form.querySelector('[data-bar-type]');
  var barCount = form.querySelector('[data-bar-count]');
  var checkIcon = itemsEl.querySelector('svg') ? itemsEl.querySelector('svg').outerHTML : '';

  function val(name) {
    var el = form.querySelector('[name="' + name + '"]:checked') || form.querySelector('[name="' + name + '"]:not([type="radio"]):not([type="checkbox"])');
    return el ? el.value : '';
  }

  function update() {
    var typeKey = cfg.types[val('type')] ? val('type') : Object.keys(cfg.types)[0];
    var type = cfg.types[typeKey];

    // zobraz len polia a skupiny, ktoré patria k zvolenému typu
    form.querySelectorAll('[data-show-for]').forEach(function (el) {
      el.hidden = el.getAttribute('data-show-for').split(' ').indexOf(typeKey) === -1;
    });
    form.querySelectorAll('[data-types]').forEach(function (el) {
      el.hidden = el.getAttribute('data-types').split(' ').indexOf(typeKey) === -1;
    });

    var list = [type.label];

    if (type.pages) {
      var pages = Math.max(1, Math.min(type.pages.max, parseInt(val('pages'), 10) || type.pages.included));
      if (pagesOut) pagesOut.textContent = pages;
      list.push(cfg.i18n.pages.replace('%d', pages));
    }
    if (type.products && cfg.products[val('products')]) {
      list.push(cfg.products[val('products')]);
    }

    var chosen = Array.prototype.map.call(form.querySelectorAll('[name="features[]"]:checked'), function (el) { return el.value; });
    Object.keys(cfg.groups).forEach(function (gKey) {
      var g = cfg.groups[gKey];
      if (g.types.indexOf(typeKey) === -1) return;
      Object.keys(g.items).forEach(function (key) {
        if (chosen.indexOf(key) !== -1) list.push(g.items[key]);
      });
    });

    var langs = parseInt(val('languages'), 10) || 0;
    if (langs > 0 && cfg.languages[langs]) list.push(cfg.i18n.languages + ': ' + cfg.languages[langs]);

    var care = val('care');
    if (care && care !== cfg.careNone && cfg.care[care]) list.push(cfg.care[care]);
    if (form.querySelector('[name="express"]:checked')) list.push(cfg.express);

    itemsEl.innerHTML = '';
    list.forEach(function (label) {
      var li = document.createElement('li');
      li.innerHTML = checkIcon;
      var span = document.createElement('span');
      span.textContent = label;
      li.appendChild(span);
      itemsEl.appendChild(li);
    });

    if (barType) barType.textContent = list[0];
    if (barCount) {
      barCount.hidden = list.length < 2;
      barCount.textContent = '+' + (list.length - 1);
    }
  }

  form.addEventListener('change', update);
  form.addEventListener('input', function (e) {
    if (e.target.type === 'range') update();
  });
  update();
})();
