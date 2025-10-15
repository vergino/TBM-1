<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['phone'])) {
    $phone = trim($_POST['phone']);
    $phone = preg_replace('/\D/', '', $phone); 

    $username = $_SESSION['user']; 
    $userFile = __DIR__ . "/../../users/$username.json";

    if (file_exists($userFile)) {
        $userData = json_decode(file_get_contents($userFile), true);

        $userData['ewallet'] = [
            'provider' => 'GCash',
            'phone' => $phone
        ];

        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));

        $_SESSION['ewallet_linked'] = true;
    }
}

header("Location: acc.php");
exit();