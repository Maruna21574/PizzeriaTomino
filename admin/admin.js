/**
 * Administrácia - potvrdenie mazania a náhľad vybraných fotiek pred nahraním.
 */
(function () {
  'use strict';

  // Tlačidlá s data-confirm (zmazanie) sa pýtajú na potvrdenie.
  document.addEventListener('click', function (e) {
    var button = e.target.closest('[data-confirm]');
    if (button && !window.confirm(button.getAttribute('data-confirm'))) {
      e.preventDefault();
    }
  });

  // Náhľad fotiek vybraných v <input type="file" data-preview>.
  document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
    var preview = document.createElement('div');
    preview.className = 'admin-preview';
    input.insertAdjacentElement('afterend', preview);

    input.addEventListener('change', function () {
      preview.innerHTML = '';
      Array.prototype.slice.call(input.files || [], 0, 30).forEach(function (file) {
        if (!/^image\//.test(file.type)) return;
        var img = document.createElement('img');
        img.alt = file.name;
        img.src = URL.createObjectURL(file);
        img.onload = function () { URL.revokeObjectURL(img.src); };
        preview.appendChild(img);
      });
    });
  });

  // Počas nahrávania fotiek zablokujeme tlačidlá, aby sa formulár neodoslal dvakrát.
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var hasFiles = Array.prototype.some.call(form.querySelectorAll('input[type="file"]'), function (input) {
        return input.files && input.files.length;
      });
      if (!hasFiles) return;
      setTimeout(function () {
        form.querySelectorAll('button[type="submit"]').forEach(function (button) { button.disabled = true; });
        var note = document.createElement('p');
        note.className = 'admin-alert admin-alert--warning';
        note.textContent = 'Nahrávam fotky, chvíľu strpenia…';
        form.appendChild(note);
      }, 0);
    });
  });
})();
