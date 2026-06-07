<?php
require_once 'db.php';
requireLogin();
$user = (int)$_SESSION['user_id'];
$orders = $conn->query("SELECT * FROM orders WHERE user_id=$user ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Orders</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Your Orders</h2>
  <?php if($orders->num_rows==0): ?>
    <p>No orders yet.</p>
  <?php else: while($o=$orders->fetch_assoc()): ?>
    <div class="order-card">
      <h3>Order #<?= $o['id'] ?> — <?= htmlspecialchars($o['status']) ?> — <?= $o['created_at'] ?></h3>
      <?php $its = $conn->query("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id=p.id WHERE oi.order_id=".$o['id']); ?>
      <ul>
      <?php while($i=$its->fetch_assoc()): ?>
        <li><?= htmlspecialchars($i['name'] ?: 'Product #'.$i['product_id']) ?> x <?= $i['quantity'] ?> = $<?= number_format($i['price']*$i['quantity'],2) ?></li>
      <?php endwhile; ?>
      </ul>
      <p><strong>Total: $<?= number_format($o['total'],2) ?></strong></p>
    </div>
  <?php endwhile; endif; ?>
</div>
<div>
  <br>
  <br>
  <br>
  <a href="index.php"> Go To Home Page</a>
      </div>
<?php include 'footer.php'; ?>
</body>
</html>