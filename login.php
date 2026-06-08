<?php
require_once 'db.php';

session_start();

// simple login check
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("
        SELECT id, password
        FROM users
        WHERE email = :email
    ");

    $stmt->execute([
        ':email' => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            header("Location: index.php");
            exit;

        } else {
            $error = "Incorrect password.";
        }

    } else {
        $error = "User not found.";
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="auth-form container">
  <h2>Login</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post">

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password" required>

    <button class="btn" type="submit">Login</button>

  </form>

  <p>
    Don't have an account?
    <a href="register.php">Register</a>
  </p>

  <p>
    <a href="index.php">Go To Home Page</a>
  </p>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
