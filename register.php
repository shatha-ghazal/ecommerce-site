<?php
require_once 'db.php';
if(isLoggedIn()){ header("Location:index.php"); exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $conn->real_escape_string(trim($_POST['name']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $phone = $conn->real_escape_string(trim($_POST['phone']));
    $password = $_POST['password'];
    if(strlen($password) < 6){ $error = "Password must be at least 6 characters."; }
    else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users(name,email,password,phone) VALUES(?,?,?,?)");
        $stmt->bind_param('ssss',$name,$email,$hash,$phone);
        if($stmt->execute()){
            header("Location: login.php"); exit;
        } else {
            $error = "Registration failed. Email may already be used.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'header.php'; ?>
<div class="auth-form container">
  <h2>Register</h2>
  <?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="post" novalidate>
    <input type="text" name="name" placeholder="Full name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password (min 6 chars)" required>
    <input type="text" name="phone" placeholder="Phone (optional)">
    <button type="submit" class="btn">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>