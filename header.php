<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : "Sweet Crumbs Bakery"; ?></title>
<link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a href="/index.php" class="brand">🍞 Sweets & Crumbs</a>
    <nav class="nav">
      <a href="/index.php">Home</a>
      <a href="/cart.php">
        Cart (<span id="cart-count">
          <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>
        </span>)
      </a>
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="hello">Hello, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></span>
        <?php if ($_SESSION['user']['role']==='admin'): ?>
          <a href="/products.php">Admin</a>
        <?php endif; ?>
        <a href="/logout.php">Logout</a>
      <?php else: ?>
        <a href="/login.php">Login</a>
        <a href="/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
