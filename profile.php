<?php
require_once 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$uid = $_SESSION['user_id'];

/* ---------------- FETCH USER ---------------- */
$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = :id
");

$stmt->execute([
    ':id' => $uid
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

/* ---------------- UPDATE PROFILE ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    $stmt = $conn->prepare("
        UPDATE users
        SET name = :name,
            email = :email,
            phone = :phone
        WHERE id = :id
    ");

    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':id' => $uid
    ]);

    header("Location: profile.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Profile</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div style="background-color:#eca5e996">
<?php include 'header.php'; ?>
</div>

<div class="container">

  <h2>Profile</h2>

  <form method="post">

    <input type="text" name="name"
           value="<?= htmlspecialchars($user['name']) ?>"
           required>

    <input type="email" name="email"
           value="<?= htmlspecialchars($user['email']) ?>"
           required>

    <input type="text" name="phone"
           value="<?= htmlspecialchars($user['phone']) ?>">

    <button class="btn" type="submit">Save</button>

    <a href="logout.php"
       class="btn"
       style="background:#c00;margin-left:8px;">
      Logout
    </a>

  </form>

</div>

<br><br>

<a href="index.php">Go To Home Page</a>

<?php include 'footer.php'; ?>

</body>
</html>
