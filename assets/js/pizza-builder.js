/**
 * Interaktívne skladanie vlastnej pizze (vlastna-pizza.php).
 * Kliknutím na prísadu sa pridá/odoberie zo zoznamu vybraných prísad
 * a prepočíta sa cena. Finálna cena sa vždy znovu overí na serveri
 * v process_order.php.
 */
(function () {
  'use strict';

  var SVG_NS = 'http://www.w3.org/2000/svg';
  var XLINK_NS = 'http://www.w3.org/1999/xlink';

  function createToppingIcon(id, className) {
    var svg = document.createElementNS(SVG_NS, 'svg');
    svg.setAttribute('class', className);
    var use = document.createElementNS(SVG_NS, 'use');
    use.setAttributeNS(XLINK_NS, 'xlink:href', '#topping-' + id);
    use.setAttribute('href', '#topping-' + id);
    svg.appendChild(use);
    return svg;
  }

  document.addEventListener('DOMContentLoaded', function () {
    var root = document.getElementById('pizzaBuilder');
    var cfg = window.PT_BUILDER;
    if (!root || !cfg) return;

    var priceEl = document.getElementById('builderPrice');
    var addBtn = document.getElementById('builderAddToCart');
    var listEl = document.getElementById('builderSelectedList');
    var emptyHint = document.getElementById('builderEmptyHint');

    var selected = {};

    function toppingById(id) {
      return cfg.toppings.find(function (t) { return t.id === id; });
    }

    function money(value) {
      return value.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
    }

    function updatePrice() {
      var total = cfg.base.price;
      Object.keys(selected).forEach(function (id) {
        var t = toppingById(id);
        if (t) total += t.price;
      });
      priceEl.textContent = money(total);
    }

    function renderList() {
      var ids = Object.keys(selected);

      listEl.querySelectorAll('.builder-summary__row').forEach(function (row) { row.remove(); });
      emptyHint.hidden = ids.length > 0;

      ids.forEach(function (id) {
        var topping = toppingById(id);
        if (!topping) return;

        var row = document.createElement('li');
        row.className = 'builder-summary__row';
        row.dataset.topping = id;

        row.appendChild(createToppingIcon(id, 'builder-summary__icon'));

        var name = document.createElement('span');
        name.className = 'builder-summary__name';
        name.textContent = topping.name;
        row.appendChild(name);

        var price = document.createElement('span');
        price.className = 'builder-summary__price';
        price.textContent = '+' + money(topping.price);
        row.appendChild(price);

        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'builder-summary__remove';
        removeBtn.setAttribute('aria-label', 'Odobrať ' + topping.name);
        removeBtn.textContent = '×';
        row.appendChild(removeBtn);

        listEl.appendChild(row);
      });
    }

    function toggleTopping(id) {
      var chip = root.querySelector('.topping-chip[data-topping="' + id + '"]');
      if (selected[id]) {
        delete selected[id];
        if (chip) chip.classList.remove('topping-chip--active');
      } else {
        selected[id] = true;
        if (chip) chip.classList.add('topping-chip--active');
      }
      renderList();
      updatePrice();
    }

    root.addEventListener('click', function (e) {
      var chip = e.target.closest('.topping-chip[data-topping]');
      if (chip) {
        toggleTopping(chip.getAttribute('data-topping'));
      }
    });

    listEl.addEventListener('click', function (e) {
      var removeBtn = e.target.closest('.builder-summary__remove');
      if (!removeBtn) return;
      var row = removeBtn.closest('.builder-summary__row');
      if (row) toggleTopping(row.dataset.topping);
    });

    addBtn.addEventListener('click', function () {
      var ids = Object.keys(selected);
      var names = ids.map(function (id) { return toppingById(id).name; });
      var price = cfg.base.price + ids.reduce(function (sum, id) { return sum + toppingById(id).price; }, 0);
      var cartId = 'custom-' + (ids.length ? ids.slice().sort().join('-') : 'plain');
      var name = ids.length ? 'Vlastná pizza' : 'Vlastná pizza (základ)';

      window.PTCart.add(cartId, name, price, 1, { custom: true, toppings: ids, toppingNames: names });
      if (window.PTCart.toast) window.PTCart.toast('Vlastná pizza bola pridaná do košíka');

      var originalLabel = addBtn.textContent;
      addBtn.textContent = 'Pridané do košíka';
      window.setTimeout(function () { addBtn.textContent = originalLabel; }, 1200);
    });

    updatePrice();
  });
})();
