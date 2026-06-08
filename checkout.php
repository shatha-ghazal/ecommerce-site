<?php
require_once 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ---------------- FETCH CART ITEMS ---------------- */
$stmt = $conn->prepare("
    SELECT c.id AS cart_id,
           c.quantity,
           p.*
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = :user_id
");

$stmt->execute([
    ':user_id' => $user_id
]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;

foreach ($items as $it) {
    $total += $it['price'] * $it['quantity'];
}

/* ---------------- PLACE ORDER ---------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $address = trim($_POST['address']);
    $payment = trim($_POST['payment_method']);

    $status = 'Pending';

    // insert order
    $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total, status, shipping_address, payment_method)
        VALUES (:user_id, :total, :status, :address, :payment)
    ");

    $stmt->execute([
        ':user_id' => $user_id,
        ':total' => $total,
        ':status' => $status,
        ':address' => $address,
        ':payment' => $payment
    ]);

    $order_id = $conn->lastInsertId();

    // insert order items
    foreach ($items as $it) {

        $ins = $conn->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price)
            VALUES (:order_id, :product_id, :quantity, :price)
        ");

        $ins->execute([
            ':order_id' => $order_id,
            ':product_id' => $it['id'],
            ':quantity' => $it['quantity'],
            ':price' => $it['price']
        ]);
    }

    // clear cart
    $clear = $conn->prepare("
        DELETE FROM cart WHERE user_id = :user_id
    ");

    $clear->execute([
        ':user_id' => $user_id
    ]);

    header("Location: order-confirmation.php?id=" . $order_id);
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Checkout</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="container">

  <h2>Checkout</h2>

  <?php if (empty($items)): ?>

    <p>Your cart is empty. <a href="index.php">Shop now</a></p>

  <?php else: ?>

    <form method="post">

      <h3>Shipping Address</h3>
      <textarea name="address" required rows="4"
                placeholder="Full shipping address"></textarea>

      <h3>Payment Method</h3>
      <select name="payment_method" required>
        <option value="Credit Card">Credit Card</option>
        <option value="PayPal">PayPal</option>
        <option value="Cash on Delivery">Cash on Delivery</option>
      </select>

      <h3>Order Summary</h3>

      <?php foreach ($items as $it): ?>
        <p>
          <?= htmlspecialchars($it['name']) ?>
          x <?= $it['quantity'] ?>
          = $<?= number_format($it['price'] * $it['quantity'], 2) ?>
        </p>
      <?php endforeach; ?>

      <h3>Total: $<?= number_format($total, 2) ?></h3>

      <button class="btn" type="submit">Place Order</button>

    </form>

  <?php endif; ?>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
