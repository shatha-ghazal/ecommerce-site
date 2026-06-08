<?php
require_once 'db.php';

$search = $_GET['search'] ?? '';

$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE name LIKE :search
    ORDER BY created_at DESC
");

$stmt->execute([
    ':search' => "%$search%"
]);

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<?php include 'header.php'; ?>

<div style="background-image:url('b_1.jpg'); height:450px; width:100%; background-size:cover; background-position:center;">
  <p class="h" style="float:right; font-size:79px;margin-right:170px;">
    Welcome to<br> Phone Hub
  </p>
</div>

<div class="container">
  <div class="products-grid">

    <?php foreach ($products as $p): ?>

      <div class="product-card">

        <img style="height:170px;width:199px;padding:10px"
             src="<?= htmlspecialchars($p['image'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>"
             alt="<?= htmlspecialchars($p['name']) ?>">

        <h3><?= htmlspecialchars($p['name']) ?></h3>

        <p>$<?= number_format((float)$p['price'], 2) ?></p>

        <a class="btn" href="product.php?id=<?= $p['id'] ?>">
          View
        </a>

      </div>

    <?php endforeach; ?>

  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
