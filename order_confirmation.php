<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    die("Invalid order ID.");
}

/* ---------------- GET ORDER ---------------- */
$stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE id = :id
      AND user_id = :user_id
");

$stmt->execute([
    ':id' => $order_id,
    ':user_id' => $_SESSION['user_id']
]);

$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found.");
}

/* ---------------- GET ORDER ITEMS ---------------- */
$stmt = $conn->prepare("
    SELECT oi.*, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = :order_id
");

$stmt->execute([
    ':order_id' => $order_id
]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<?php include "header.php"; ?>

<div class="confirmation">

  <h2>Thank you for your order!</h2>

  <p>Your order ID is <strong>#<?= $order_id ?></strong></p>

  <p>
    Total:
    <strong>$<?= number_format($order['total'], 2) ?></strong>
  </p>

  <h3>Order Items:</h3>

  <ul>
    <?php foreach ($items as $item): ?>
      <li>
        <?= htmlspecialchars($item['name']) ?>
        × <?= $item['quantity'] ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <p>
    Status:
    <strong><?= htmlspecialchars($order['status']) ?></strong>
  </p>

</div>

<?php include "footer.php"; ?>

</body>
</html>
