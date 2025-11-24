<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$id = (int)($_POST['product_id'] ?? 0);
if (!$id) {
  echo json_encode(['ok'=>false,'error'=>'Missing product id']);
  exit;
}

$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id=? AND is_active=1");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
  echo json_encode(['ok'=>false,'error'=>'Product not found']);
  exit;
}

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (!isset($_SESSION['cart'][$id])) {
  $_SESSION['cart'][$id] = [
    'name'  => $p['name'],
    'price' => (float)$p['price'],
    'qty'   => 0
  ];
}
$_SESSION['cart'][$id]['qty']++;

$count = array_sum(array_column($_SESSION['cart'], 'qty'));
echo json_encode(['ok'=>true,'count'=>$count]);
