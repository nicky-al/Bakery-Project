<?php
// public/product.php
session_start();
require_once __DIR__ . '/../config/db.php';
$page_title = "Product details";
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND is_active=1");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo "<p>Product not found.</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }
?>
<div class="row" style="margin-top:20px">
  <div>
    <img src="<?php echo htmlspecialchars($p['image'] ?: '/assets/images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" style="width:100%;max-width:520px;border-radius:12px;border:1px solid #eee">
  </div>
  <div>
    <h1><?php echo htmlspecialchars($p['name']); ?></h1>
    <?php if (!empty($p['category'])): ?><p><span class="badge"><?php echo htmlspecialchars($p['category']); ?></span></p><?php endif; ?>
    <p><?php echo nl2br(htmlspecialchars($p['description'] ?? '')); ?></p>
    <p class="price" style="font-size:1.4rem">$<?php echo number_format($p['price'], 2); ?></p>
<button class="btn" data-add="<?php echo (int)$p['id']; ?>">Add to Cart</button>
  </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
