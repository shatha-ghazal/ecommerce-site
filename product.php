<?php
require_once 'db.php';
//requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param('i',$id); $stmt->execute(); $product = $stmt->get_result()->fetch_assoc();
if(!$product) { die('Product not found'); }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $qty = max(1,(int)$_POST['quantity']);
    $user = $_SESSION['user_id'];
    // if exists, update quantity
    $check = $conn->prepare("SELECT id FROM cart WHERE user_id=? AND product_id=?");
    $check->bind_param('ii',$user,$id); $check->execute(); $r = $check->get_result();
    if($r->num_rows){
        $row = $r->fetch_assoc();
        $conn->query("UPDATE cart SET quantity = quantity + $qty WHERE id = ".(int)$row['id']);
    } else {
        $ins = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)");
        $ins->bind_param('iii',$user,$id,$qty); $ins->execute();
    }
    header("Location: cart.php"); exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title><?= htmlspecialchars($product['name']) ?></title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <div style="display:flex;gap:20px;">
    <div style="flex:1;">
      <img style="height:250px;width:300px;padding:15px"src="<?= htmlspecialchars($product['image'] ?: 'https://via.placeholder.com/600x400?text=No+Image') ?>" style="max-width:100%;border-radius:8px;">
    </div>
    <div style="flex:1;">
      <div style="margin-left:-170px">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p ><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <p><strong>$<?= number_format((float)$product['price'],2) ?></strong></p>
      <label>Quantity</label>
      <input type="number" name="quantity" value="1" min="1" style="width:110px;">   
    </div>
      <form method="post">
        
              <button class="btn" type="submit" style=" position:absolute;width:150px;height:40px;margin-left:250px;background-color:#eb79e5c7;top:175px;margin-bottom:30px;border:none">Add to Cart</button>

      </form>
      <br>
      <br>
      <br>
      <br>
      <a style="position:absolute;bottom:40%;left:40%;color:darkpurple"href="index.php">Go To Home Page</a>
    </div>
  </div>
</div>
<div style="margin-top:200px">
<?php include 'footer.php'; ?>
</div>
</body>
</html>