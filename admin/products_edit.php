<?php
// admin/products_edit.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();
$page_title = "Edit product";
require_once __DIR__ . '/../includes/header.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo "<p>Not found</p>"; require_once __DIR__ . '/../includes/footer.php'; exit; }
?>
<h1>Edit product #<?php echo (int)$p['id']; ?></h1>
<form method="post" class="form" action="/admin/products.php?action=update">
  <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>">
  <label>Name</label><input class="input" name="name" value="<?php echo htmlspecialchars($p['name']); ?>" required>
  <label>Description</label><textarea class="input" name="description" rows="3"><?php echo htmlspecialchars($p['description']); ?></textarea>
  <div class="row">
    <div><label>Price</label><input class="input" name="price" type="number" min="0" step="0.01" value="<?php echo htmlspecialchars($p['price']); ?>" required></div>
    <div><label>Category</label><input class="input" name="category" value="<?php echo htmlspecialchars($p['category']); ?>"></div>
  </div>
  <div class="row">
    <div><label>Stock</label><input class="input" name="stock" type="number" min="0" value="<?php echo (int)$p['stock']; ?>"></div>
    <div><label>Image URL</label><input class="input" name="image" value="<?php echo htmlspecialchars($p['image']); ?>"></div>
  </div>
  <label><input type="checkbox" name="is_active" <?php echo $p['is_active'] ? 'checked' : ''; ?>> Active</label>
  <button class="btn" type="submit">Save changes</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
