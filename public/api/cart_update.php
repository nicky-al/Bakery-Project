<?php
// api/cart_update.php
session_start();
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
$op = $_GET['op'] ?? 'inc';

if (!isset($_SESSION['cart'][$id])) { 
  header("Location: /cart.php"); 
  exit; 
}

switch ($op) {
  case 'inc': 
    $_SESSION['cart'][$id]['qty']++; 
    break;
  case 'dec': 
    $_SESSION['cart'][$id]['qty'] = max(1, $_SESSION['cart'][$id]['qty'] - 1); 
    break;
  case 'remove': 
    unset($_SESSION['cart'][$id]); 
    break;
}

header("Location: /cart.php");
