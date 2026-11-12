(function () {
  'use strict';

  var form = document.getElementById('booking-form');
  if (!form) {
    return;
  }

  var price = parseInt(form.dataset.price, 10);
  var max = parseInt(form.dataset.max, 10);
  var list = document.getElementById('passengers');
  var addBtn = document.getElementById('add-passenger');
  var totalEl = document.getElementById('total');

  function formatMoney(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' FCFA';
  }

  function refresh() {
    var count = list.querySelectorAll('input').length;
    totalEl.textContent = formatMoney(price * count);
    addBtn.disabled = count >= max;
  }

  addBtn.addEventListener('click', function () {
    var count = list.querySelectorAll('input').length;
    if (count >= max) {
      return;
    }
    var label = document.createElement('label');
    label.textContent = 'Passager ' + (count + 1);
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'passengers[]';
    input.minLength = 2;
    input.maxLength = 80;
    input.required = true;
    label.appendChild(input);
    list.appendChild(label);
    input.focus();
    refresh();
  });

  refresh();
})();
