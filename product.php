<?php
require_once 'db.php';

session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute([':id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die('Product not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $qty = max(1, (int)$_POST['quantity']);
    $user = $_SESSION['user_id'];

    // check if item exists in cart
    $check = $conn->prepare("
        SELECT id, quantity
        FROM cart
        WHERE user_id = :user AND product_id = :product
    ");

    $check->execute([
        ':user' => $user,
        ':product' => $id
    ]);

    $cartItem = $check->fetch(PDO::FETCH_ASSOC);

    if ($cartItem) {

        $update = $conn->prepare("
            UPDATE cart
            SET quantity = quantity + :qty
            WHERE id = :id
        ");

        $update->execute([
            ':qty' => $qty,
            ':id' => $cartItem['id']
        ]);

    } else {

        $insert = $conn->prepare("
            INSERT INTO cart (user_id, product_id, quantity)
            VALUES (:user, :product, :qty)
        ");

        $insert->execute([
            ':user' => $user,
            ':product' => $id,
            ':qty' => $qty
        ]);
    }

    header("Location: cart.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($product['name']) ?></title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div class="container">
  <div style="display:flex;gap:20px;">

    <div style="flex:1;">
      <img src="<?= htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/600x400?text=No+Image') ?>"
           style="height:250px;width:300px;padding:15px;border-radius:8px;">
    </div>

    <div style="flex:1;">

      <div style="margin-left:-170px;">
        <h2><?= htmlspecialchars($product['name']) ?></h2>

        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

        <p><strong>$<?= number_format((float)$product['price'],2) ?></strong></p>

        <form method="post">
          <label>Quantity</label>
          <input type="number" name="quantity" value="1" min="1" style="width:110px;">

          <button class="btn" type="submit"
                  style="width:150px;height:40px;margin-left:20px;background-color:#eb79e5c7;border:none;">
            Add to Cart
          </button>
        </form>

        <br><br>

        <a href="index.php" style="color:darkpurple;">
          Go To Home Page
        </a>

      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
