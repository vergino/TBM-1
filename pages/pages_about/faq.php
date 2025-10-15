<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FAQ - TrueBlossomMart</title>
  <link rel="stylesheet" href="contactstyle.css" />
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
          <a href="about.php">ABOUT US</a>
		  <a href="../pages_orders/addtocart.php" class="orders-link">ORDERS<span id="cart-count" class="cart-count">0</span></a>
          <a href="../pages_account/acc.php" id="accountLink">ACCOUNT</a>
		  
        </div>
    </div>
	
	<div class="logo">
          <a href="../../homepage.php"><img src="../../assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
		
</header>

  <main class="aboutLayout">
    <aside class="sidebar">
      <a href="about.php">About Us</a>
      <a href="contact.php">Contact Support</a>
	  <a href="faq.php">FAQ</a>
    </aside>

  <div class="abtBox">
  <section class="tbminfo">
    <h1>FAQ</h1>

	  <div class="faq-item">
      <p class="faq-question"><strong>Q:</strong> What payment methods are there? ▼</p>
      <p class="faq-answer"><strong>A:</strong> Cash-On-Delivery and E-Wallet!</p>
	  </div>
   
      <div class="faq-item">
      <p class="faq-question"><strong>Q:</strong> Will the flowers arrive fresh? ▼</p>
      <p class="faq-answer"><strong>A:</strong> Yes, shoppers are given the choice if they want <strong>Same Day Delivery or Reservation! </strong></p>
	  </div>

      <div class="faq-item">
      <p class="faq-question"><strong>Q:</strong> Who will deliver the flowers? ▼</p>
      <p class="faq-answer"><strong>A:</strong> Our online shop will utilize different couriers such as <strong>Lalamove, J&T Express and LBC! </strong></p>
      </div>
	  
	  
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

	<script>
  document.querySelectorAll(".faq-question").forEach(question => {
	question.addEventListener("click", () => {
		const answer = question.nextElementSibling;
		answer.style.display = (answer.style.display === "block") ? "none" : "block";
	});
});
</script>

</body>
</html>
