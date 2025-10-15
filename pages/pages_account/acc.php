
<?php
session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../../login.php");
    exit;
}

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

$successMessage = "";
if (!empty($userData['ewallet']) && isset($userData['ewallet']['provider']) && strtolower($userData['ewallet']['provider']) === "gcash") {
    $successMessage = "✅ Your GCash account (" . htmlspecialchars($userData['ewallet']['phone']) . ") is linked!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saveProfile'])) {
    $newUsername = trim($_POST['username']);
    $newPassword = trim($_POST['password']);

    if (!empty($_FILES['profilePic']['name'])) {
        $targetDir = __DIR__ . "/../../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = $username . "_" . basename($_FILES['profilePic']['name']);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFile)) {
            $userData['profile_pic'] = $fileName;
        }
    }

    if ($newUsername && $newUsername !== $username) {
        $newUserFile = __DIR__ . "/../../users/$newUsername.json";
        $_SESSION['user'] = $newUsername;
        $username = $newUsername;
        rename($userFile, $newUserFile);
        $userFile = $newUserFile;
    }

    if ($newPassword) {
        $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
    }

    if (isset($_POST['ewallet'])) {
        $userData['ewallet'] = $_POST['ewallet'];
    }

    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));
}

$userData = json_decode(file_get_contents($userFile), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Profile – TrueBlossomMart</title>
  <link rel="stylesheet" href="acc.css" />
 
</head>
<body>
<?php if (!empty($successMessage)): ?>
  <div class="success-popup">
    <?= $successMessage ?>
  </div>
  <script>
    setTimeout(() => {
      document.querySelector('.success-popup').style.display = 'none';
    }, 3000);
  </script>
  <?php endif; ?>
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
    <nav class="sidebar">
      <a href="acc.php">Profile</a>
      <a href="wishlist.php">Wishlist</a>
      <a href="orders.php">Order Tracking</a>
	  <form method="POST">
		<button type="submit" name="logout">Logout</button>
	  </form>
    </nav>

   <form id="profileForm" class="profile-card" method="POST" enctype="multipart/form-data">
  <h1>PROFILE</h1>
  
  <img id="profilePreview" class="profile-pic-circle"
       src="<?php echo !empty($userData['profile_pic']) 
                ? '../../uploads/' . htmlspecialchars($userData['profile_pic']) 
                : '../../assets/images/default.png'; ?>"
       alt="Profile Picture" />

  <div class="profile-row">
    <label for="profile-pic">Profile Pic:</label>
    <input type="file" id="profile-pic" name="profilePic" accept="image/*" />
    <label for="profile-pic" class="file-label">Choose File</label>
  </div>

  <div class="profile-row">
    <label for="username">Change Username:</label>
    <input type="text" id="username" name="username"
           value="<?php echo htmlspecialchars($username); ?>" required />
  </div>

  <div class="profile-row">
    <label for="password">Change Password:</label>
    <input type="password" id="password" name="password" placeholder="••••••••" />
  </div>

 <section class="setting-card settings-section">
        <div class="settings-row">
          <span>E-Wallet:</span>
          <div style="margin-left: auto;">
            <button type="button" class="styled-btn" onclick="window.location.href='Gcashformockup.html'">Link my account</button>
          </div>
        </div>
      </section>

      <div class="payment-icons" id="paymentIcons">
        <img src="https://orangemagazine.ph/wp-content/uploads/2019/07/received_336957633908411.jpeg" data-method="gcash" alt="GCash" />
   
      </div>


  <button type="submit" name="saveProfile" class="save-btn">Save Changes</button>
</form>

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

  const fileInput = document.getElementById("profile-pic");
  const previewImg = document.getElementById("profilePreview");

  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
});
</script>

</body>
</html>
