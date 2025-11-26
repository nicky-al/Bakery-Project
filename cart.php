<?php
// public/cart.php
session_start();
$page_title = "Your cart";
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>
<h1>Your Cart</h1>
<?php if (!$cart): ?>
  <p>Your cart is empty.</p>
<?php else: ?>
  <form action="/checkout.php" method="post" class="form">
    <table class="table">
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th><th></th></tr>
      </thead>
      <tbody>
      <?php foreach ($cart as $pid => $item):
          $subtotal = $item['qty'] * $item['price'];
          $total += $subtotal;
      ?>
        <tr>
          <td><?php echo htmlspecialchars($item['name']); ?></td>
          <td>
            <div class="actions">
              <a class="btn" href="/api/cart_update.php?id=<?php echo (int)$pid; ?>&op=dec">-</a>
              <span style="padding:8px 10px;border:1px solid #eee;border-radius:8px;background:#fff">
                <?php echo (int)$item['qty']; ?>
              </span>
              <a class="btn" href="/api/cart_update.php?id=<?php echo (int)$pid; ?>&op=inc">+</a>
            </div>
          </td>
          <td>$<?php echo number_format($item['price'],2); ?></td>
          <td>$<?php echo number_format($subtotal,2); ?></td>
          <td><a class="btn" href="/api/cart_update.php?id=<?php echo (int)$pid; ?>&op=remove">Remove</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr><td colspan="3" style="text-align:right">Total</td>
        <td>$<?php echo number_format($total,2); ?></td><td></td></tr>
      </tfoot>
    </table>
    <button class="btn" type="submit">Proceed to checkout</button>
  </form>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
