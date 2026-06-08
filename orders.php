<?php
require_once 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ---------------- FETCH ORDERS ---------------- */
$stmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE user_id = :user_id
    ORDER BY created_at DESC
");

$stmt->execute([
    ':user_id' => $user_id
]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Orders</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="container">

  <h2>Your Orders</h2>

  <?php if (empty($orders)): ?>

    <p>No orders yet.</p>

  <?php else: ?>

    <?php foreach ($orders as $o): ?>

      <div class="order-card">

        <h3>
          Order #<?= $o['id'] ?>
          — <?= htmlspecialchars($o['status']) ?>
          — <?= $o['created_at'] ?>
        </h3>

        <?php
        // fetch order items safely
        $itemsStmt = $conn->prepare("
            SELECT oi.*, p.name
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ");

        $itemsStmt->execute([
            ':order_id' => $o['id']
        ]);

        $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <ul>
          <?php foreach ($items as $i): ?>
            <li>
              <?= htmlspecialchars($i['name'] ?: 'Product #' . $i['product_id']) ?>
              x <?= $i['quantity'] ?>
              = $<?= number_format($i['price'] * $i['quantity'], 2) ?>
            </li>
          <?php endforeach; ?>
        </ul>

        <p>
          <strong>Total: $<?= number_format($o['total'], 2) ?></strong>
        </p>

      </div>

    <?php endforeach; ?>

  <?php endif; ?>

</div>

<br><br>

<a href="index.php" style="margin-left:20px;">
  Go To Home Page
</a>

<?php include 'footer.php'; ?>

</body>
</html>
