<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$order_id = intval($_GET['order_id'] ?? 0);
if ($order_id <= 0) {
  echo "Invalid order ID.";
  exit;
}

$stmt = $pdo->prepare("SELECT o.*, a.address_line1, a.city, a.country
                       FROM orders o
                       JOIN addresses a ON o.address_id = a.id
                       WHERE o.id = ? AND o.user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
  echo "Order not found.";
  exit;
}

$stmt = $pdo->prepare("SELECT oi.*, p.name
                       FROM order_items oi
                       JOIN products p ON oi.product_id = p.id
                       WHERE oi.order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Confirmation</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<?php include "header.php"; ?>

<div class="confirmation">
  <h2>Thank you for your order!</h2>
  <p>Your order ID is <strong>#<?php echo $order_id; ?></strong>.</p>
  <p>Shipping to: <?php echo htmlspecialchars($order['address_line1'] . ', ' . $order['city'] . ', ' . $order['country']); ?></p>
  <p>Total: <strong>$<?php echo number_format($order['total'], 2); ?></strong></p>

  <h3>Order Items:</h3>
  <ul>
    <?php foreach ($items as $item): ?>
      <li><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></li>
    <?php endforeach; ?>
  </ul>

  <p>Status: <strong><?php echo htmlspecialchars($order['status']); ?></strong></p>
</div>

<?php include "footer.php"; ?>
</body>
</html>