(function () {
  'use strict';

  /* image preview */
  document.querySelectorAll('[data-image-input]').forEach(function (input) {
    var previewId = input.getAttribute('data-image-input');
    var preview = document.getElementById(previewId);
    if (!preview) return;
    input.addEventListener('change', function () {
      if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
      }
    });
  });

  /* auto-slug from title */
  var titleInput = document.querySelector('[data-slug-source]');
  var slugInput = document.querySelector('[data-slug-target]');
  if (titleInput && slugInput) {
    var map = { 'á':'a','ä':'a','č':'c','ď':'d','é':'e','í':'i','ĺ':'l','ľ':'l','ň':'n','ó':'o','ô':'o','ŕ':'r','š':'s','ť':'t','ú':'u','ý':'y','ž':'z' };
    var slugify = function (text) {
      text = text.toLowerCase().replace(/[áäčďéíĺľňóôŕšťúýž]/g, function (m) { return map[m] || m; });
      return text.replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    };
    titleInput.addEventListener('input', function () {
      if (!slugInput.dataset.touched) slugInput.value = slugify(titleInput.value);
    });
    slugInput.addEventListener('input', function () { slugInput.dataset.touched = '1'; });
  }

  /* confirm delete */
  document.querySelectorAll('[data-confirm]').forEach(function (el) {
    el.addEventListener('submit', function (e) {
      if (!window.confirm(el.getAttribute('data-confirm'))) {
        e.preventDefault();
      }
    });
  });
})();
