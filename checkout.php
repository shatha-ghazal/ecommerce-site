<?php
require_once 'dbe.php';
requireLogin();

// calculate cart and total
$sql = "SELECT c.id as cart_id, c.quantity, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=".(int)$_SESSION['user_id'];
$res = $conn->query($sql);
$items = []; $total = 0;
while($r = $res->fetch_assoc()){
    $items[] = $r;
    $total += $r['price'] * $r['quantity'];
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $address = $conn->real_escape_string($_POST['address']);
    $payment = $conn->real_escape_string($_POST['payment_method']);
    $user = (int)$_SESSION['user_id'];

    // insert order
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status, shipping_address, payment_method) VALUES (?,?,?,?,?)");
    $status = 'Pending';
    $stmt->bind_param('idsss', $user, $total, $status, $address, $payment);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // add order items
    foreach($items as $it){
        $p = (int)$it['id'];
        $q = (int)$it['quantity'];
        $price = (float)$it['price'];
        $ins = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?,?,?,?)");
        $ins->bind_param('iiid', $order_id, $p, $q, $price);
        $ins->execute();
    }

    // clear cart
    $conn->query("DELETE FROM cart WHERE user_id = $user");

    header("Location: order-confirmation.php?id=".$order_id);
    exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Checkout</title><link rel="stylesheet" href="style.css"></head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2>Checkout</h2>
  <?php if(empty($items)): ?>
    <p>Your cart is empty. <a href="index.php">Shop now</a></p>
  <?php else: ?>
  <form method="post">
    <h3>Shipping Address</h3>
    <textarea name="address" required placeholder="Full shipping address" rows="4"></textarea>
    <h3>Payment Method</h3>
    <select name="payment_method" required>
      <option value="Credit Card">Credit Card</option>
      <option value="PayPal">PayPal</option>
      <option value="Cash on Delivery">Cash on Delivery</option>
    </select>

    <h3>Order Summary</h3>
    <?php foreach($items as $it): ?>
      <p><?= htmlspecialchars($it['name']) ?> x <?= $it['quantity'] ?> = $<?= number_format($it['price']*$it['quantity'],2) ?></p>
    <?php endforeach; ?>
    <h3>Total: $<?= number_format($total,2) ?></h3>

    <button class="btn" type="submit">Place Order</button>
  </form>
  <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>