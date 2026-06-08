<?php
// Make sure isLoggedIn() exists somewhere included globally
?>

<header class="h_1" style="padding:12px 20px;border-bottom:1px solid #eee;">
  <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;">

    <div>
      <a href="index.php" style="text-decoration:none;color:#333;font-weight:bold;font-size:18px;">
        Phone Hub
      </a>
    </div>

    <div>

      <form method="get" action="index.php" style="display:inline-block;margin-right:12px;">
        <div class="input-c" style="display:flex;align-items:center;gap:10px;">
          <input class="p" type="text" name="search" placeholder="Search products..."
                 style="padding:6px 8px;font-size:16px;flex:1;">
          <button class="b" type="submit"
                  style="padding:6px 10px;background-color:#eb79e5c7;border:none;font-size:16px;cursor:pointer;">
            Search
          </button>
        </div>
      </form>

      <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>

        <a href="cart.php" style="margin-right:10px;color:darkpurple;">Cart</a>
        <a href="orders.php" style="margin-right:10px;color:darkpurple;">Orders</a>
        <a href="profile.php" style="margin-right:10px;color:darkpurple;">Profile</a>
        <a href="logout.php" style="color:#c00;">Logout</a>

      <?php else: ?>

        <a href="login.php" style="margin-right:10px;">Login</a>
        <a href="register.php">Register</a>

      <?php endif; ?>

    </div>
  </div>
</header>
