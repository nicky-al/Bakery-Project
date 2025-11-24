<?php
// register.php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/csrf.php';

$error = null;
$success = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($name) < 2) $error = "Name is too short.";
    if (!$error && strlen($password) < 6) $error = "Password must be at least 6 characters.";

    if (!$error) {
        try {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash) VALUES (?,?,?)");
            $stmt->execute([$name,$email,$hash]);
            $success = "Account created. You can now login.";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { $error = "Email already registered."; }
            else { $error = "Registration failed."; }
        }
    }
}
?>
<h1>Register</h1>
<form method="post" class="form">
  <?php csrf_field(); ?>
  <label>Name</label>
  <input class="input" type="text" name="name" required>
  <label>Email</label>
  <input class="input" type="email" name="email" required>
  <label>Password</label>
  <input class="input" type="password" name="password" required minlength="6">
  <?php if ($error): ?><p class="error"><?php echo htmlspecialchars($error); ?></p><?php endif; ?>
  <?php if ($success): ?><p class="success"><?php echo htmlspecialchars($success); ?></p><?php endif; ?>
  <button class="btn" type="submit">Create account</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
