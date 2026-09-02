/**
 * Jadro nákupného košíka - ukladá položky do localStorage a je zdieľané
 * naprieč všetkými stránkami (tlačidlá "Pridať do košíka", odznak v hlavičke).
 * Samotné vykreslenie obsahu košíka na stránke objednávky rieši order.js.
 */
(function (window) {
  'use strict';

  var STORAGE_KEY = 'pt_cart';

  function getCart() {
    try {
      var raw = window.localStorage.getItem(STORAGE_KEY);
      var items = raw ? JSON.parse(raw) : [];
      return Array.isArray(items) ? items : [];
    } catch (e) {
      return [];
    }
  }

  function saveCart(items) {
    try {
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
    } catch (e) {
      /* localStorage nedostupný (napr. súkromné okno) - košík sa v tomto prípade nezachová. */
    }
    document.dispatchEvent(new CustomEvent('pt-cart-updated', { detail: { items: items } }));
  }

  function addItem(id, name, price, qty, meta) {
    qty = qty || 1;
    var items = getCart();
    var existing = items.find(function (i) { return i.id === id; });
    if (existing) {
      existing.qty += qty;
    } else {
      var item = { id: id, name: name, price: parseFloat(price), qty: qty };
      if (meta) item.meta = meta;
      items.push(item);
    }
    saveCart(items);
    return items;
  }

  function setQty(id, qty) {
    var items = getCart();
    qty = parseInt(qty, 10);
    if (!qty || qty < 1) {
      items = items.filter(function (i) { return i.id !== id; });
    } else {
      items.forEach(function (i) { if (i.id === id) i.qty = Math.min(qty, 50); });
    }
    saveCart(items);
    return items;
  }

  function removeItem(id) {
    var items = getCart().filter(function (i) { return i.id !== id; });
    saveCart(items);
    return items;
  }

  function clearCart() {
    saveCart([]);
  }

  function totalCount(items) {
    items = items || getCart();
    return items.reduce(function (sum, i) { return sum + i.qty; }, 0);
  }

  function totalPrice(items) {
    items = items || getCart();
    return items.reduce(function (sum, i) { return sum + i.qty * parseFloat(i.price); }, 0);
  }

  window.PTCart = {
    get: getCart,
    save: saveCart,
    add: addItem,
    setQty: setQty,
    remove: removeItem,
    clear: clearCart,
    totalCount: totalCount,
    totalPrice: totalPrice,
    toast: function (text) { showToast(text); },
  };

  function updateBadge() {
    var badge = document.getElementById('cartBadge');
    if (!badge) return;
    var count = totalCount();
    if (count > 0) {
      badge.textContent = count > 99 ? '99+' : String(count);
      badge.hidden = false;
    } else {
      badge.hidden = true;
    }
  }

  function formatMoney(value) {
    return value.toLocaleString('sk-SK', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' €';
  }

  function pluralizePolozky(count) {
    if (count === 1) return 'položka';
    if (count >= 2 && count <= 4) return 'položky';
    return 'položiek';
  }

  function updateMiniCart() {
    var bar = document.getElementById('miniCart');
    if (!bar) return;

    // Na stránke objednávky je už vidno plný košík - plávajúci panel by len prekážal.
    if (document.body.classList.contains('page-order')) {
      bar.hidden = true;
      document.body.classList.remove('has-mini-cart');
      return;
    }

    var items = getCart();
    var count = totalCount(items);

    if (count === 0) {
      bar.hidden = true;
      document.body.classList.remove('has-mini-cart');
      return;
    }

    document.getElementById('miniCartCount').textContent = String(count);
    document.getElementById('miniCartLabel').textContent = pluralizePolozky(count);
    document.getElementById('miniCartTotal').textContent = formatMoney(totalPrice(items));
    bar.hidden = false;
    document.body.classList.add('has-mini-cart');
  }

  function showToast(text) {
    var toast = document.getElementById('ptToast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'ptToast';
      toast.className = 'pt-toast';
      document.body.appendChild(toast);
    }
    toast.textContent = text;
    toast.classList.add('pt-toast--visible');
    window.clearTimeout(toast._hideTimer);
    toast._hideTimer = window.setTimeout(function () {
      toast.classList.remove('pt-toast--visible');
    }, 1800);
  }

  document.addEventListener('DOMContentLoaded', function () {
    updateBadge();
    updateMiniCart();

    document.addEventListener('click', function (e) {
      var btn = e.target.closest('[data-add-to-cart]');
      if (!btn) return;
      e.preventDefault();
      var id = btn.getAttribute('data-id');
      var name = btn.getAttribute('data-name');
      var price = btn.getAttribute('data-price');
      addItem(id, name, price, 1);
      showToast(name + ' bola pridaná do košíka');

      btn.classList.add('btn--added');
      window.setTimeout(function () { btn.classList.remove('btn--added'); }, 600);
    });
  });

  document.addEventListener('pt-cart-updated', function () {
    updateBadge();
    updateMiniCart();
  });
})(window);
