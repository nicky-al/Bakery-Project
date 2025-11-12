<?php
// admin/products.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/csrf.php';
require_admin();
$page_title = "Manage products";
require_once __DIR__ . '/../includes/header.php';

$action = $_GET['action'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') { verify_csrf(); }

// Create
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $cat = trim($_POST['category'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $image = trim($_POST['image'] ?? '');

    $stmt = $pdo->prepare("INSERT INTO products (name,description,price,image,category,stock,is_active) VALUES (?,?,?,?,?,?,1)");
    $stmt->execute([$name,$desc,$price,$image,$cat,$stock]);
    header("Location: /admin/products.php?m=created"); exit;
}

// Update
if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $desc = trim($_POST['description'] ?? '');
    $cat = trim($_POST['category'] ?? '');
    $stock = (int)($_POST['stock'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $active = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE products SET name=?, description=?, price=?, image=?, category=?, stock=?, is_active=? WHERE id=?");
    $stmt->execute([$name,$desc,$price,$image,$cat,$stock,$active,$id]);
    header("Location: /admin/products.php?m=updated"); exit;
}

// Delete
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    if ($id) {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
        $stmt->execute([$id]);
    }
    header("Location: /admin/products.php?m=deleted"); exit;
}

// Load products
$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<h1>Products</h1>

<section class="form">
  <h2>Add new product</h2>
  <form method="post" action="/admin/products.php?action=create">
    <?php csrf_field(); ?>
    <label>Name</label><input class="input" name="name" required>
    <label>Description</label><textarea class="input" name="description" rows="3"></textarea>
    <div class="row">
      <div><label>Price</label><input class="input" name="price" type="number" min="0" step="0.01" required></div>
      <div><label>Category</label><input class="input" name="category" placeholder="Bread / Pastry / Cake"></div>
    </div>
    <div class="row">
      <div><label>Stock</label><input class="input" name="stock" type="number" min="0" value="0"></div>
      <div><label>Image URL</label><input class="input" name="image" placeholder="/assets/images/sourdough.jpg"></div>
    </div>
    <button class="btn" type="submit">Create</button>
  </form>
</section>

<section style="margin-top:24px">
  <h2>Current products</h2>
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Active</th><th>Actions</th></tr></thead>
    <tbody>
      <?php foreach ($products as $p): ?>
      <tr>
        <td><?php echo (int)$p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['name']); ?></td>
        <td>$<?php echo number_format($p['price'],2); ?></td>
        <td><?php echo (int)$p['stock']; ?></td>
        <td><?php echo $p['is_active'] ? 'Yes' : 'No'; ?></td>
        <td class="actions">
          <a class="btn" href="/admin/products_edit.php?id=<?php echo (int)$p['id']; ?>">Edit</a>
          <a class="btn" href="/admin/products.php?action=delete&id=<?php echo (int)$p['id']; ?>" onclick="return confirm('Delete product?')">Delete</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
