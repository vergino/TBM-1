<?php
// pages_orders/orders.php
session_start();
$currentUser = $_SESSION['username'] ?? null;
if (!$currentUser) {
    header("Location: ../pages_account/login.html");
    exit;
}

$ordersFile = __DIR__ . "/orders.json";
$cartFile   = __DIR__ . "/cart.json";

/* Helper: save JSON safely */
function saveJson($path, $data) {
    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/* If the checkout form posted here, create the order */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rName'])) {
    // read cart
    $cartData = [];
    if (file_exists($cartFile)) $cartData = json_decode(file_get_contents($cartFile), true) ?: [];

    $userCart = $cartData[$currentUser] ?? [];
    if (!empty($userCart)) {
        $orders = [];
        if (file_exists($ordersFile)) $orders = json_decode(file_get_contents($ordersFile), true) ?: [];

        // calculate total server-side
        $total = 0;
        foreach ($userCart as $it) $total += floatval($it['price']) * intval($it['quantity']);

        $newOrder = [
            'id' => time() . rand(10,99),
            'user' => $currentUser,
            'items' => $userCart,
            'receiver' => [
                'name' => htmlspecialchars($_POST['rName']),
                'email' => htmlspecialchars($_POST['rEmail']),
                'contact' => htmlspecialchars($_POST['rContact']),
                'region' => htmlspecialchars($_POST['rRegion'] ?? ''),
                'province' => htmlspecialchars($_POST['rProvince'] ?? ''),
                'city' => htmlspecialchars($_POST['rCity'] ?? ''),
                'postal' => htmlspecialchars($_POST['rPostal'] ?? ''),
                'address' => htmlspecialchars($_POST['rAddress'] ?? ''),
                'note' => htmlspecialchars($_POST['rNote'] ?? '')
            ],
            'payment' => htmlspecialchars($_POST['payment'] ?? 'N/A'),
            'shipping' => htmlspecialchars($_POST['shipping'] ?? 'N/A'),
            'expectedDate' => ($_POST['shipping'] ?? '') === 'reservation' ? ($_POST['rDate'] ?? '') : '',
            'total' => $total + 50 + (($_POST['shipping'] ?? '') === 'same' ? 40 : 0), // mimic fees
            'date' => date('Y-m-d H:i:s'),
            'status' => 'placed'
        ];

        $orders[] = $newOrder;
        saveJson($ordersFile, $orders);

        // clear user's cart
        $cartData[$currentUser] = [];
        saveJson($cartFile, $cartData);

        // redirect to same page (PRG pattern) so reload doesn't repost
        header("Location: orders.php?placed=1");
        exit;
    }
}

// read orders for display
$orders = [];
if (file_exists($ordersFile)) $orders = json_decode(file_get_contents($ordersFile), true) ?: [];
$userOrders = array_filter($orders, fn($o) => ($o['user'] ?? '') === $currentUser);

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your Orders - TrueBlossomMart</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../universalflowerstyle.css">
  <style>
    .order-entry { border:1px solid #ddd; padding:14px; margin:12px 0; border-radius:6px; }
    .order-header { margin-bottom:8px; }
    .order-items li { margin:6px 0; }
    .cancel-btn { background:#e74c3c;color:white;border:none;padding:8px 12px;border-radius:6px;cursor:pointer; }
  </style>
</head>
<body>
<header><!-- header here if needed --></header>

<main style="max-width:980px;margin:18px auto;padding:8px;">
  <h1>Order Tracking</h1>

  <?php if (isset($_GET['placed'])): ?>
    <div style="background:#e6ffed;border:1px solid #baf5c8;padding:12px;border-radius:6px;">Order placed successfully.</div>
  <?php endif; ?>

  <?php if (empty($userOrders)): ?>
    <p>No orders found. <a href="../pages_flower/flowers.php">Place an order</a></p>
  <?php else: ?>
    <?php foreach ($userOrders as $order): ?>
      <div class="order-entry">
        <div class="order-header">
          <strong>Order #<?= htmlspecialchars($order['id']) ?></strong> &nbsp;
          <small><?= htmlspecialchars($order['date']) ?></small><br>
          <strong>Total:</strong> ₱<?= number_format($order['total'],2) ?><br>
          <strong>Payment:</strong> <?= htmlspecialchars(ucfirst($order['payment'])) ?><br>
          <strong>Shipping:</strong> <?= htmlspecialchars(ucfirst($order['shipping'])) ?><br>
          <?php if (!empty($order['expectedDate'])): ?>
            <strong>Delivery Date:</strong> <?= htmlspecialchars($order['expectedDate']) ?><br>
          <?php endif; ?>
        </div>

        <div>
          <p><strong>Receiver:</strong> <?= htmlspecialchars($order['receiver']['name'] ?? '') ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($order['receiver']['email'] ?? '') ?></p>
          <p><strong>Contact:</strong> <?= htmlspecialchars($order['receiver']['contact'] ?? '') ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($order['receiver']['address'] ?? '') ?>,
             <?= htmlspecialchars($order['receiver']['city'] ?? '') ?>,
             <?= htmlspecialchars($order['receiver']['province'] ?? '') ?>,
             <?= htmlspecialchars($order['receiver']['region'] ?? '') ?>,
             <?= htmlspecialchars($order['receiver']['postal'] ?? '') ?></p>
        </div>

        <ul class="order-items">
          <?php foreach ($order['items'] as $it): ?>
            <li><?= htmlspecialchars($it['name']) ?> ×<?= intval($it['quantity']) ?> — ₱<?= number_format($it['price'] * $it['quantity'],2) ?></li>
          <?php endforeach; ?>
        </ul>

        <form method="post" action="cancel_order.php" onsubmit="return confirm('Cancel order #<?= htmlspecialchars($order['id']) ?>?');">
          <input type="hidden" name="orderId" value="<?= htmlspecialchars($order['id']) ?>">
          <button type="submit" class="cancel-btn">Cancel Order</button>
        </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

</main>
</body>
</html>
