<?php
session_start();

// Require login
if (!isset($_SESSION['user'])) {
    header("Location: ../../pages_account/login.html");
    exit;
}

$username = $_SESSION['user'];
// user JSON file (one file per user)
$userFile = __DIR__ . "/../../users/{$username}.json";

// Ensure user JSON exists
if (!file_exists($userFile)) {
    // create basic structure if missing
    $base = ['username' => $username, 'cart' => [], 'linkedMethod' => null, 'linkedData' => null];
    file_put_contents($userFile, json_encode($base, JSON_PRETTY_PRINT));
}

$userData = json_decode(file_get_contents($userFile), true) ?: [];
if (!isset($userData['cart']) || !is_array($userData['cart'])) {
    $userData['cart'] = [];
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $newItem = [
        'name' => trim($_POST['add']),
        'price' => trim($_POST['price']),
        'imageUrl' => trim($_POST['imageUrl']),
        'quantity' => max(1, (int)($_POST['quantity'] ?? 1))
    ];

    $alreadyInCart = false;
    foreach ($userData['cart'] as &$c) {
        if (is_array($c) && isset($c['name']) && $c['name'] === $newItem['name']) {
            $c['quantity'] = (int)$c['quantity'] + $newItem['quantity'];
            $alreadyInCart = true;
            break;
        }
    }
    unset($c);

    if (!$alreadyInCart) {
        $userData['cart'][] = $newItem;
    }

    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    header("Location: addtocart.php");
    exit;
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'])) {
    $removeItem = $_POST['remove'];
    $userData['cart'] = array_values(array_filter($userData['cart'], function($item) use ($removeItem) {
        if (!is_array($item)) return $item !== $removeItem;
        return ($item['name'] ?? '') !== $removeItem;
    }));
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    header("Location: addtocart.php");
    exit;
}

// Increase / decrease quantity
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['increase'])) {
        $index = (int)$_POST['increase'];
        if (isset($userData['cart'][$index]['quantity'])) {
            $userData['cart'][$index]['quantity'] = (int)$userData['cart'][$index]['quantity'] + 1;
        }
    } elseif (isset($_POST['decrease'])) {
        $index = (int)$_POST['decrease'];
        if (isset($userData['cart'][$index]['quantity']) && (int)$userData['cart'][$index]['quantity'] > 1) {
            $userData['cart'][$index]['quantity'] = (int)$userData['cart'][$index]['quantity'] - 1;
        }
    }

    // Save and redirect to avoid form re-submission
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
    header("Location: addtocart.php");
    exit;
}

$cart = $userData['cart'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Cart – TrueBlossomMart</title>
  <link rel="stylesheet" href="addtocart.css">
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
          <a href="addtocart.php" class="orders-link">ORDERS</a>
          <a href="../pages_account/acc.php" id="accountLink">ACCOUNT</a>
        </div>
    </div>

    <div class="logo">
      <a href="../../homepage.php"><img src="../../assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
</header>

<section class="cart-container">
  <h1>MY CART</h1>

  <?php if (empty($cart)): ?>
    <p class="empty-cart-message">Your cart is empty.</p>
    <button class="add-btn" onclick="window.location.href='../pages_flower/flowers.php'">Add flowers to cart</button>
  <?php else: ?>
    <?php
      $total = 0;
      foreach ($cart as $index => $item):
        if (!is_array($item)) continue;
        // normalize price (allow strings like "₱1,350")
        $priceNumeric = (float) preg_replace('/[^\d.]/', '', $item['price']);
        $subtotal = $priceNumeric * (int)$item['quantity'];
        $total += $subtotal;
    ?>
      <div class="cart-item">
        <a href="../pages_flower/flower_<?= urlencode(str_replace(' ', '', $item['name'])) ?>/<?= urlencode(str_replace(' ', '', $item['name'])) ?>.php">
          <img src="<?= htmlspecialchars($item['imageUrl']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
        </a>
        <div class="item-info">
          <h3><?= htmlspecialchars($item['name']) ?></h3>
          <p>₱<?= number_format($priceNumeric, 2) ?></p>

          <form method="post" style="display:inline;">
            <div class="quantity-control">
              <button type="submit" name="decrease" value="<?= $index ?>">-</button>
              <input type="text" value="<?= (int)$item['quantity'] ?>" readonly>
              <button type="submit" name="increase" value="<?= $index ?>">+</button>
            </div>
          </form>
        </div>

        <form method="post" style="display:inline;">
          <button type="submit" name="remove" value="<?= htmlspecialchars($item['name']) ?>" class="remove-item">Remove</button>
        </form>
      </div>
    <?php endforeach; ?>

    <div class="total-checkout" style="margin-top:30px; text-align:center;">
      <p id="total-price">Total: ₱<?= number_format($total, 2) ?></p>
      <a href="checkout.php" class="checkout-link">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</section>

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

document.addEventListener("DOMContentLoaded", () => {
  const dropbtn = document.querySelector(".dropbtn");
  const dropdown = document.querySelector(".dropdown-content");
  dropbtn.addEventListener("click", () => dropdown.classList.toggle("show"));
  window.addEventListener("click", (event) => {
    if (!event.target.closest(".dropdown")) dropdown.classList.remove("show");
  });
});
</script>
</body>
</html>
