<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

$username = $_SESSION['user'];
$userFile = __DIR__ . "/../../users/$username.json";

if (!file_exists($userFile)) {
    die("User data not found.");
}

$userData = json_decode(file_get_contents($userFile), true);

if (!isset($userData['wishlist'])) {
    $userData['wishlist'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $newItem = [
        'name' => $_POST['add'],
        'price' => $_POST['price'],
        'imageUrl' => $_POST['imageUrl'],
        'quantity' => $_POST['quantity']
    ];

    $alreadyInWishlist = false;
    foreach ($userData['wishlist'] as $w) {
        if (is_array($w) && $w['name'] === $newItem['name']) {
            $alreadyInWishlist = true;
            break;
        }
    }

    if (!$alreadyInWishlist) {
        $userData['wishlist'][] = $newItem;
        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    }

    header("Location: wishlist.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $removeItem = $_POST['remove'];
    $userData['wishlist'] = array_filter($userData['wishlist'], function($item) use ($removeItem) {
        return is_array($item) ? $item['name'] !== $removeItem : $item !== $removeItem;
    });
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    header("Location: wishlist.php");
    exit;
}

$wishlist = $userData['wishlist'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Wishlist – TrueBlossomMart</title>
  <link rel="stylesheet" href="wishlist.css" />
</head>
<body>
<header class="tbmHeader">
    <div class="dropdown">
		<button class="dropbtn">
			<img src="../../assets/images/dropbtn_Logo.png" alt="DropDown Button">
		</button>
        <div class="dropdown-content">
		 <div class="smalllogo">
          <img src="../../assets/images/LogoSmall.png" alt="TBMart Small">
		 </div>
		  <a href="../../homepage.php">HOME</a>
          <a href="../pages_flower/flowers.php">PRODUCTS</a>
          <a href="../pages_about/about.php">ABOUT US</a>
		  <a href="../pages_orders/addtocart.php" class="orders-link">ORDERS<span id="cart-count" class="cart-count">0</span></a>
          <a href="acc.php" id="accountLink">ACCOUNT</a>
        </div>
    </div>
	<div class="logo">
          <a href="../../homepage.php"><img src="../../assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
</header>

<div class="account-container">
  <nav class="sidebar" aria-label="Account settings">
    <a href="acc.php">Profile</a>
    <a href="wishlist.php" aria-current="page">Wishlist</a>
    <a href="orders.php">Order Tracking</a>
  </nav>

  <section class="wishlist">
    <h1>WISHLIST</h1>

    <?php if (empty($wishlist)): ?>
      <p>Your wishlist is empty.</p>
      <button class="add-btn" onclick="window.location.href='../pages_flower/flowers.php'">Add flowers to wishlist</button>
    <?php else: ?>
      <?php foreach ($wishlist as $item): ?>
        <div class="item">
          <?php if (is_array($item)): ?>
            <a href="../pages_flower/flower_<?= str_replace(' ', '', $item['name']) ?>/<?= str_replace(' ', '', $item['name']) ?>.php">
              <img src="<?= htmlspecialchars($item['imageUrl']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
            </a>
            <div class="details">
              <h2><?= htmlspecialchars($item['name']) ?></h2>
              <p><?= htmlspecialchars($item['price']) ?></p>
            </div>
            <form method="post">
              <button type="submit" name="remove" value="<?= htmlspecialchars($item['name']) ?>" class="remove-btn">Remove</button>
            </form>
          <?php else: ?>
            <!-- fallback for old string-only wishlist items -->
            <div class="details">
              <h2><?= htmlspecialchars($item) ?></h2>
            </div>
            <form method="post">
              <button type="submit" name="remove" value="<?= htmlspecialchars($item) ?>" class="remove-btn">Remove</button>
            </form>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
</div>

<script>
async function updateCartCount() {
  const cartCountElement = document.getElementById('cart-count');
  if (!cartCountElement) return;

  try {
    const response = await fetch('../pages_orders/cartcount.php'); 
    const data = await response.json();
    cartCountElement.textContent = data.count;
  } catch (error) {
    console.error('Error fetching cart count:', error);
    cartCountElement.textContent = '0';
  }
}

document.addEventListener('DOMContentLoaded', updateCartCount);
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const dropbtn = document.querySelector(".dropbtn");
  const dropdown = document.querySelector(".dropdown-content");

  dropbtn.addEventListener("click", () => {
    dropdown.classList.toggle("show");
  });

  window.addEventListener("click", (event) => {
    if (!event.target.closest(".dropdown")) {
      dropdown.classList.remove("show");
    }
  });
});
</script>

</body>
</html>
