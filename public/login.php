<?php
// public/login.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = ['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']];
        header("Location: /index.php");
        exit;
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<h1>Login</h1>
<form method="post" class="form">
  <label>Email</label>
  <input class="input" type="email" name="email" required>
  <label>Password</label>
  <input class="input" type="password" name="password" required minlength="6">
  <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
  <button class="btn" type="submit">Login</button>
  <p>No account? <a href="/register.php">Register</a></p>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
