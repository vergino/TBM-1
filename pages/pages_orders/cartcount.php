<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(["count" => 0]);
    exit;
}

$username = $_SESSION['user'];
$userFile = __DIR__ . "/../../users/$username.json";

if (!file_exists($userFile)) {
    echo json_encode(["count" => 0]);
    exit;
}

$userData = json_decode(file_get_contents($userFile), true);
$cart = isset($userData['cart']) ? $userData['cart'] : [];

$totalItems = 0;
foreach ($cart as $item) {
    if (is_array($item) && isset($item['quantity'])) {
        $totalItems += (int)$item['quantity'];
    } else {
      
        $totalItems++;
    }
}

echo json_encode(["count" => $totalItems]);
