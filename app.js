// assets/js/app.js
console.log("app.js loaded successfully");

document.addEventListener('click', (e) => {
  const target = e.target.closest('[data-add]');
  if (!target) return;

  const id = target.getAttribute('data-add');
  const formData = new FormData();
  formData.append('product_id', id);

  fetch('/api/cart_add.php', {
    method: 'POST',
    body: formData,
    credentials: 'same-origin'
  })
    .then(r => r.json())
    .then(d => {
      if (d.ok) {
        const el = document.getElementById('cart-count');
        if (el) el.textContent = d.count;
        alert('Item added to cart!');
      } else {
        alert(d.error || 'Failed to add to cart');
      }
    })
    .catch(() => alert('Network error'));
});
