<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/csrf.php';

verify_csrf();

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header("Location: /public/cart.php"); exit; }

$total = 0;
foreach ($cart as $item) { $total += $item['price'] * $item['qty']; }

$pdo->beginTransaction();
try {
    $uid = $_SESSION['user']['id'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([$uid, $total]);
    $orderId = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price) VALUES (?,?,?,?)");
    foreach ($cart as $pid => $item) {
        $stmtItem->execute([$orderId, $pid, $item['qty'], $item['price']]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo "Checkout failed: " . htmlspecialchars($e->getMessage());
    exit;
}

$page_title = "Order confirmed";
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Thank you for your order!</h1>
<p>Your order #<?php echo (int)$orderId; ?> has been placed. Total: <strong>$<?php echo number_format($total,2); ?></strong></p>
<p>We’ll start baking right away! You’ll see the order in your account (if logged in).</p>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
