<?php
require_once 'db.php';
//requireLogin();

// Remove
if(isset($_GET['remove'])) {
    $rid = (int)$_GET['remove'];
    $conn->query("DELETE FROM cart WHERE id=$rid AND user_id=".(int)$_SESSION['user_id']);
    header("Location: cart.php"); exit;
}

// Update quantities
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])){
    foreach($_POST['quantities'] as $cart_id => $q){
        $cid = (int)$cart_id; $qty = max(1,(int)$q);
        $conn->query("UPDATE cart SET quantity=$qty WHERE id=$cid AND user_id=".(int)$_SESSION['user_id']);
    }
    header("Location: cart.php"); exit;
}

// fetch cart
$sql = "SELECT c.id as cart_id, c.quantity, p.* FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id=".(int)$_SESSION['user_id'];
$res = $conn->query($sql);
$total = 0;
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Cart</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <div style="background-color:#eca5e996">
<?php include 'header.php'; ?>
</div>
<div class="container">
  <h2>Your Cart</h2>
  <form method="post">
    <?php if($res->num_rows == 0): ?>
      <p>Your cart is empty. <a href="index.php">Shop now</a></p>
    <?php else: while($row = $res->fetch_assoc()):
        $subtotal = $row['price'] * $row['quantity']; $total += $subtotal;
    ?>
      <div class="cart-item">
        <img style="height:199px;width:250px;padding:10px"src="<?= htmlspecialchars($row['image'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        <div style="flex:1;">
          <h3><?= htmlspecialchars($row['name']) ?></h3>
          <p>$<?= number_format((float)$row['price'],2) ?> x 
            <input style="width:80px;" type="number" name="quantities[<?= $row['cart_id'] ?>]" value="<?= $row['quantity'] ?>" min="1">
            = $<?= number_format($subtotal,2) ?>
          </p>
          <a href="cart.php?remove=<?= $row['cart_id'] ?>">Remove</a>
        </div>
      </div>
      <hr>
    <?php endwhile; ?>
    <h3>Total: $<?= number_format($total,2) ?></h3>
    <button class="btn" type="submit">Update Cart</button>
    <a class="btn" href="checkout.php" style="margin-left:8px;">Proceed to Checkout</a>
    <br>
    <br>
    <br>
    <a href="index.php">Go To Home Page</a>
    <?php endif; ?>
  </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>