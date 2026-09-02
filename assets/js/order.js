/**
 * Logika stránky objednavka.php: vykreslenie košíka, prepočet súm
 * a odoslanie objednávky na process_order.php (bez platobnej brány,
 * platba je vždy na dobierku).
 */
(function () {
  'use strict';

  var TRASH_ICON_SVG = '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" ' +
    'stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
    '<polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>' +
    '<path d="M10 11v6M14 11v6"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>';

  document.addEventListener('DOMContentLoaded', function () {
    var cfg = window.PT_CONFIG || { deliveryFee: 1.5, deliveryFreeFrom: 15, minOrder: 8, currency: '€' };

    var itemsWrap = document.getElementById('cartItems');
    var emptyWrap = document.getElementById('cartEmpty');
    var sumSubtotal = document.getElementById('sumSubtotal');
    var sumDelivery = document.getElementById('sumDelivery');
    var sumTotal = document.getElementById('sumTotal');
    var form = document.getElementById('orderForm');
    var submitBtn = document.getElementById('submitOrderBtn');
    var formMessage = document.getElementById('orderFormMessage');

    function money(value) {
      return value.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + cfg.currency;
    }

    function render() {
      var items = window.PTCart.get();

      if (!items.length) {
        itemsWrap.innerHTML = '';
        emptyWrap.hidden = false;
      } else {
        emptyWrap.hidden = true;
        itemsWrap.innerHTML = items.map(function (item) {
          var lineTotal = item.qty * item.price;
          var toppings = item.meta && item.meta.toppingNames && item.meta.toppingNames.length
            ? '<span class="cart-item__toppings">Prísady: ' + escapeHtml(item.meta.toppingNames.join(', ')) + '</span>'
            : '';
          return (
            '<div class="cart-item" data-id="' + item.id + '">' +
              '<div class="cart-item__info">' +
                '<h3>' + escapeHtml(item.name) + '</h3>' +
                '<span class="cart-item__price">' + money(item.price) + ' / ks</span>' +
                toppings +
              '</div>' +
              '<div class="cart-item__qty">' +
                '<button type="button" class="qty-btn" data-action="dec" aria-label="Znížiť počet">−</button>' +
                '<input type="number" class="qty-input" min="1" max="50" value="' + item.qty + '" aria-label="Počet kusov">' +
                '<button type="button" class="qty-btn" data-action="inc" aria-label="Zvýšiť počet">+</button>' +
              '</div>' +
              '<div class="cart-item__total">' + money(lineTotal) + '</div>' +
              '<button type="button" class="cart-item__remove" aria-label="Odstrániť položku">' + TRASH_ICON_SVG + '</button>' +
            '</div>'
          );
        }).join('');
      }

      var subtotal = window.PTCart.totalPrice(items);
      var delivery = items.length === 0 ? 0 : (subtotal >= cfg.deliveryFreeFrom ? 0 : cfg.deliveryFee);
      var total = subtotal + delivery;

      sumSubtotal.textContent = money(subtotal);
      sumDelivery.textContent = delivery === 0 ? 'Zadarmo' : money(delivery);
      sumTotal.textContent = money(total);

      submitBtn.disabled = items.length === 0 || subtotal < cfg.minOrder;
      if (items.length > 0 && subtotal < cfg.minOrder) {
        submitBtn.textContent = 'Minimálna objednávka je ' + money(cfg.minOrder);
      } else {
        submitBtn.textContent = 'Odoslať objednávku na dobierku';
      }
    }

    function escapeHtml(str) {
      var div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    }

    itemsWrap.addEventListener('click', function (e) {
      var row = e.target.closest('.cart-item');
      if (!row) return;
      var id = row.getAttribute('data-id');

      if (e.target.closest('[data-action="inc"]')) {
        var current = window.PTCart.get().find(function (i) { return i.id === id; });
        window.PTCart.setQty(id, (current ? current.qty : 0) + 1);
        render();
      } else if (e.target.closest('[data-action="dec"]')) {
        var cur = window.PTCart.get().find(function (i) { return i.id === id; });
        window.PTCart.setQty(id, (cur ? cur.qty : 1) - 1);
        render();
      } else if (e.target.closest('.cart-item__remove')) {
        window.PTCart.remove(id);
        render();
      }
    });

    itemsWrap.addEventListener('change', function (e) {
      if (!e.target.classList.contains('qty-input')) return;
      var row = e.target.closest('.cart-item');
      var id = row.getAttribute('data-id');
      window.PTCart.setQty(id, e.target.value);
      render();
    });

    document.addEventListener('pt-cart-updated', render);
    render();

    function showMessage(text, type) {
      formMessage.textContent = text;
      formMessage.hidden = false;
      formMessage.className = 'alert alert--' + type;
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      var items = window.PTCart.get();
      if (!items.length) {
        showMessage('Váš košík je prázdny.', 'error');
        return;
      }

      var payload = {
        fullName: form.fullName.value.trim(),
        phone: form.phone.value.trim(),
        email: form.email.value.trim(),
        street: form.street.value.trim(),
        city: form.city.value.trim(),
        note: form.note.value.trim(),
        payment: form.payment.value,
        agree: form.agree.checked,
        website: form.website.value,
        cart: items.map(function (i) {
          var line = { id: i.id, qty: i.qty };
          if (i.meta && i.meta.custom) {
            line.custom = true;
            line.toppings = i.meta.toppings;
          }
          return line;
        }),
      };

      if (!payload.fullName || !payload.phone || !payload.street || !payload.city || !payload.agree) {
        showMessage('Vyplňte prosím všetky povinné polia a odsúhlaste spracovanie údajov.', 'error');
        return;
      }

      submitBtn.disabled = true;
      submitBtn.textContent = 'Odosielam...';

      fetch('/process_order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload),
      })
        .then(function (res) { return res.json().then(function (data) { return { status: res.status, data: data }; }); })
        .then(function (result) {
          if (result.data && result.data.ok) {
            window.PTCart.clear();
            var params = new URLSearchParams({ id: result.data.orderId, total: result.data.total });
            window.location.href = '/dakujeme.php?' + params.toString();
          } else {
            showMessage((result.data && result.data.message) || 'Objednávku sa nepodarilo odoslať. Skúste to prosím znova.', 'error');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Odoslať objednávku na dobierku';
          }
        })
        .catch(function () {
          showMessage('Nastala chyba pri odosielaní. Skúste to prosím znova, alebo nám zavolajte.', 'error');
          submitBtn.disabled = false;
          submitBtn.textContent = 'Odoslať objednávku na dobierku';
        });
    });
  });
})();
