document.addEventListener('click', (e) => {
  const target = e.target.closest('[data-add]');
  if (!target) return;

  e.preventDefault();
  e.stopPropagation();   
  e.stopImmediatePropagation(); 

  const id = target.getAttribute('data-add');
  const formData = new FormData();
  formData.append('product_id', id);
function showToast(message) {
  const toast = document.getElementById('toast');
  toast.textContent = message;
  toast.classList.add('show');

  setTimeout(() => {
    toast.classList.remove('show');
  }, 2000); 
}

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
      showToast('Item added to cart!');
    } else {
      alert(d.error || 'Failed to add to cart');
    }
  })
  .catch(() => alert('Network error'));
});
