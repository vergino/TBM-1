<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = strtolower(trim($_POST['username']));
    $password = $_POST['password'];
    $email = $_POST['email'];

    $userFile = __DIR__ . "/users/$username.json";

    if (file_exists($userFile)) {
        $error = "Username already exists.";
    } else {
        $userData = [
            "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "email" => $email,
            "wishlist" => []
        ];
        file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <style>
    @font-face {
      font-family: 'BodyFont';
      src: url('assets/fonts/Montserrat/Montserrat-VariableFont_wght.ttf') format('truetype');
    }
    @font-face {
      font-family: 'HeaderFont';
      src: url('assets/fonts/Bebas/BebasNeue-Regular.ttf') format('truetype');
    }

    body {
      margin: 0;
      font-family: 'BodyFont', sans-serif;
      background: linear-gradient(#fff, #f1a59c);
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-box {
      background: #ffffff;
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0px 6px 20px rgba(0,0,0,0.2);
      width: 370px;
      text-align: center;
    }

    .register-box h2 {
      font-family: 'HeaderFont', sans-serif;
      font-size: 40px;
      color: #333;
      margin-bottom: 20px;
    }

    .register-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
      font-family: 'BodyFont', sans-serif;
      outline: none;
      transition: 0.3s;
    }

    .register-box input:focus {
      border-color: #f77c6b;
      box-shadow: 0 0 6px rgba(247, 124, 107, 0.6);
    }

    .register-box button {
      width: 100%;
      padding: 12px;
      background: #ffb131;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-size: 18px;
      cursor: pointer;
      transition: 0.3s;
      margin-top: 10px;
    }

    .register-box button:hover {
      background: #da49a7;
      transform: scale(1.05);
    }

    .register-box p {
      margin-top: 15px;
      font-size: 14px;
      color: #333;
    }

    .register-box a {
      text-decoration: none;
      color: #f77c6b;
      font-weight: bold;
      transition: 0.3s;
    }

    .register-box a:hover {
      color: #da49a7;
    }

    .error {
      color: red;
      margin-top: 10px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="register-box">
    <h2>Register</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <p><a href="login.php">Back to Login</a></p>
  </div>
</body>
</html>
