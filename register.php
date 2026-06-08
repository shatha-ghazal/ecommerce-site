<?php
require_once 'db.php';

session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        // check if email already exists
        $check = $conn->prepare("
            SELECT id
            FROM users
            WHERE email = :email
        ");

        $check->execute([
            ':email' => $email
        ]);

        if ($check->fetch()) {
            $error = "Email already registered.";
        } else {

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("
                INSERT INTO users (name, email, password, phone)
                VALUES (:name, :email, :password, :phone)
            ");

            $success = $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hash,
                ':phone' => $phone
            ]);

            if ($success) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Registration failed.";
            }
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="auth-form container">

  <h2>Register</h2>

  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <form method="post">

    <input type="text" name="name" placeholder="Full name" required>

    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password (min 6 chars)" required>

    <input type="text" name="phone" placeholder="Phone (optional)">

    <button type="submit" class="btn">Register</button>

  </form>

  <p>
    Already have an account?
    <a href="login.php">Login</a>
  </p>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
