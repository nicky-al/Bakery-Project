<?php
// public/index.php
session_start();
$page_title = "Fresh bakes — Sweet Crumbs";
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$category = $_GET['category'] ?? null;
$q = $_GET['q'] ?? null;

$sql = "SELECT * FROM products WHERE is_active=1";
$params = [];
if ($category) { $sql .= " AND category = :cat"; $params[':cat'] = $category; }
if ($q) { $sql .= " AND name LIKE :q"; $params[':q'] = '%' . $q . '%'; }
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>
<h1>Fresh from the oven</h1>
<form method="get" class="row" style="margin-top:10px">
  <input class="input" type="text" name="q" placeholder="Search pastries..."
         value="<?php echo htmlspecialchars($q ?? ''); ?>">
  <select class="input" name="category">
    <option value="">All categories</option>
    <option<?php if(($category ?? '')==='Bread') echo ' selected'; ?>>Bread</option>
    <option<?php if(($category ?? '')==='Pastry') echo ' selected'; ?>>Pastry</option>
    <option<?php if(($category ?? '')==='Cake') echo ' selected'; ?>>Cake</option>
  </select>
  <div><button class="btn" type="submit">Filter</button></div>
</form>

<div class="grid">
  <?php foreach ($products as $p): ?>
    <div class="card">
      <img src="<?php echo htmlspecialchars($p['image'] ?: '/bakery-store/assets/images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
      <div class="pad">
        <h3><?php echo htmlspecialchars($p['name']); ?></h3>
        <?php if (!empty($p['category'])): ?><span class="badge"><?php echo htmlspecialchars($p['category']); ?></span><?php endif; ?>
        <p><?php echo htmlspecialchars(mb_strimwidth($p['description'] ?? '', 0, 90, '…')); ?></p>
        <p class="price">$<?php echo number_format($p['price'], 2); ?></p>
        <div class="actions">
          <a class="btn" href="/public/product.php?id=<?php echo (int)$p['id']; ?>">View</a>
<button class="btn" data-add="<?php echo (int)$p['id']; ?>">Add to Cart</button>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
