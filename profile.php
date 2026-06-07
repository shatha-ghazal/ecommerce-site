<?php
require_once 'db.php';
requireLogin();
$uid = (int)$_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $conn->query("UPDATE users SET name='$name', email='$email', phone='$phone' WHERE id=$uid");
    header("Location: profile.php"); exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Profile</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <div style="background-color:#eca5e996">
<?php include 'header.php'; ?>
</div>
<div class="container">
  <h2>Profile</h2>
  <form method="post">
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
    <button class="btn" type="submit">Save</button>
    <a href="logout-.php" class="btn" style="background:#c00;margin-left:8px;">Logout</a>
  </form>
</div>
<div>
  <br>
  <br>
  <br>
<a href="index.php">Go To Home Page</a>
</div>
<?php include 'footer.php'; ?>
</body>
</html>