<?php
require_once 'db.php';
//requireLogin(); // optional: remove if you allow guests
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$catSql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY created_at DESC";
$res = $conn->query($catSql);
?>
<!doctype html>
<html>
  <!-- background:hsl(325, 49%, 79%) -->
<head><meta charset="utf-8"><title>Home</title><link rel="stylesheet" href="style.css"></head>
<body  >
<?php include 'header.php'; ?>

<div style=" background-image :url('b_1.jpg'); height:450px ; width:500px; background-size: cover;
  background-position: center; 
  background-repeat: no-repeat; 
  width: 100%; 
  ">
  <p class="h"style="float:right; font-size:79px;margin-right:170px;"> Welcome to<br> Phone Hub</p>
</div>
<div class="container">
  <h2></h2>
  <div class="products-grid">
    <?php while($p = $res->fetch_assoc()): ?>
      <div class="product-card">
        <img style= "height:170px;width:199px;padding:10px" src="<?= htmlspecialchars($p['image'] ?: 'https://via.placeholder.com/300x200?text=No+Image') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p>$<?= number_format((float)$p['price'],2) ?></p>
        <a style="background-color:#b739c0e9"class="btn" href="product.php?id=<?= $p['id'] ?>">View</a>
      </div>
    <?php endwhile; ?>
  </div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>