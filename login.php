<?php
require_once 'db.php';
if(isLoggedIn()){
    header("Location: index.php"); exit;
}
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res->num_rows){
        $user = $res->fetch_assoc();
        if(password_verify($password, $user['password'])){
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
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'header.php'; ?>
<div class="auth-form container">
  <h2>Login</h2>
  <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="post" novalidate>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button class="btn" type="submit">Login</button>
  </form>
  <p>Don't have an account? <a href="register-.php">Register</a></p>
  <p><a href="index.php">Go To Home Page</a></p>
</div>
<?php include 'footer.php'; ?>
</body>
</html>