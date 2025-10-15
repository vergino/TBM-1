<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Homepage - TrueBlossomMart</title>
  <link rel="stylesheet" href="homepagestyle.css">
  
</head>
<body>
<header class="tbmHeader">
    <div class="dropdown">
		<button class="dropbtn">
			<img src="assets/images/dropbtn_Logo.png" alt="DropDown Button">
		</button>
		
         <div class="dropdown-content">
		 
		 <div class="smalllogo">
          <img src="assets/images/LogoSmall.png" alt="TBMart Small">
		 </div>
		  
		  <a href="homepage.php">HOME</a>
          <a href="pages/pages_flower/flowers.php">PRODUCTS</a>
          <a href="pages/pages_about/about.php">ABOUT US</a>
		  <a href="pages/pages_orders/addtocart.php" class="orders-link">ORDERS<span id="cart-count" class="cart-count">0</span></a>
          <a href="pages/pages_account/acc.php" id="accountLink">ACCOUNT</a>
		  
        </div>
    </div>
	
	<div class="logo">
          <a href="homepage.php"><img src="assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
		
</header>

    <div class="tbmcont">
      <h1>EXPERIENCE THE BEAUTY OF BLOOMS!</h1>
      <p>Fresh bouquets, exquisite arrangements, and botanical wonders to brighten your day!</p>
      <a href="pages/pages_flower/flowers.php">View Products</a>
    </div>

    <div class="tbmFooter">
      <p>Welcome to TRUE BLOSSOM MART – your one stop shop for the finest fresh flowers and arrangements. Discover our collection of seasonal bouquets, elegant centerpieces, and unique floral designs.</p>
    </div>
	
	<script>
async function updateCartCount() {
  const cartCountElement = document.getElementById('cart-count');
  if (!cartCountElement) return;

  try {
    const response = await fetch('pages/pages_orders/cartcount.php'); 
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
