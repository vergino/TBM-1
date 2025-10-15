<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flowers - TrueBlossomMart</title>
  <link rel="stylesheet" href="flower.css" />
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
          <a href="../pages_account/acc.php" id="accountLink">ACCOUNT</a>
		  
        </div>
    </div>
	
	<div class="logo">
          <a href="../../homepage.php"><img src="../../assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
		
</header>

  <main>
    <aside class="sidebar">
  <h2 class="dropdown-toggle">CATEGORIES</h2>
  <div class="category-group filter-content">
    <button onclick="filterCategory('ready-made')">Ready Made</button>
    <button onclick="filterCategory('customized')">Customized</button>
  </div>

  <h2 class="dropdown-toggle">OCCASIONS</h3>
  <div class="category-group filter-content">
    <button onclick="filterCategory('fathers-day')">Father's Day</button>
    <button onclick="filterCategory('mothers-day')">Mother's Day</button>
    <button onclick="filterCategory('valentines')">Valentines</button>
    <button onclick="filterCategory('wedding')">Wedding</button>
    <button onclick="filterCategory('birthday')">Birthday</button>
    <button onclick="filterCategory('anniversary')">Anniversary</button>
    <button onclick="filterCategory('funeral')">Funeral</button>
  </div>

  <h2 class="dropdown-toggle">TYPES</h3>
  <div class="category-group filter-content">
    <button onclick="filterCategory('roses')">Roses</button>
    <button onclick="filterCategory('tulips')">Tulips</button>
    <button onclick="filterCategory('orchids')">Orchids</button>
    <button onclick="filterCategory('lilies')">Lilies</button>
    <button onclick="filterCategory('daisies')">Daisies</button>
  </div>

  <div class="clear">
    <button onclick="filterCategory('clear')">CLEAR</button>
  </div>
</aside>


    <section class="content">
      <div class="search-bar">
        <input type="text" placeholder="Search flowers...">
        <button>Search</button>
      </div>

      <h1>Featured Flowers</h1>
      <div class="flower-grid">
        <div class="flower-item valentines anniversary roses customized clear">
          <a href="flower_RoseBunch/rosebunch.php">
            <img src="https://m.media-amazon.com/images/I/81OXEQrFPTL.jpg" alt="Rose Bunch">
            <span>Rose Bunch <br> ₱1200</span>
          </a>
        </div>

        <div class="flower-item valentines wedding birthday customized clear">
          <a href="flower_SunFlowerBouquet/sunflowerbouquet.php">
            <img src="https://www.tinsflowershop.com/3784-large_default/sunflower-sunshine-bouquet.jpg" alt="Sun Flower Bouquet">
            <span>Sun Flower Bouquet <br> ₱1400</span>
          </a>
        </div>

        <div class="flower-item funeral ready-made clear">
          <a href="flower_FuneralFlower/funeralflower.php">
            <img src="https://fyf.tac-cdn.net/images/products/small/FYF-423.jpg?auto=webp&quality=60&width=650" alt="Funeral Flower">
            <span>Funeral Flower <br> ₱1900</span>
          </a>
        </div>

        <div class="flower-item mothers-day valentines ready-made clear">
          <a href="flower_PottedHibiscus/pottedhibiscus.php">
            <img src="https://st.hzcdn.com/simgs/83e2f8c00f9dad3e_9-9062/_.jpg" alt="Potted Hibiscus">
            <span>Potted Hibiscus <br> ₱500</span>
          </a>
        </div>

        <div class="flower-item fathers-day mothers-day birthday anniversary daisies clear">
          <a href="flower_DaisyBouquet/daisybouquet.php">
            <img src="https://asset.bloomnation.com/c_pad,d_vendor:global:catalog:product:image.png,f_auto,fl_preserve_transparency,q_auto/v1660190201/vendor/6167/catalog/product/2/0/20220613100522_file_62a7b4a2686c9_62a7b54400d97.jpeg" alt="Daisy Bouquets">
            <span>Daisy Bouquets <br> ₱1350</span>
          </a>
        </div>

        <div class="flower-item fathers-day mothers-day birthday anniversary wedding customized clear">
          <a href="flower_SmallFlowerBasket/smallflowerbasket.php">
            <img src="https://www.flowerchimp.com.ph/cdn/shop/products/Flower_Basket_White_ff78cfda-320a-4706-b244-0b88c9617da4_600x.jpg?v=1573807865" alt="Small Flower Basket">
            <span>Small Flower Basket <br> ₱1000</span>
          </a>
        </div>

        <div class="flower-item fathers-day mothers-day valentines birthday roses ready-made clear">
          <a href="flower_VasedRose/vasedrose.php">
            <img src="https://media.istockphoto.com/id/111795740/photo/bouquet-of-red-roses.jpg?s=612x612&w=0&k=20&c=PFz1-TC_64jMyPMvgiSWpeqmTNdwXmDupOPpnUDLtnc=" alt="Vase Roses">
            <span>Vase Roses <br> ₱1500</span>
          </a>
        </div>

        <div class="flower-item mothers-day fathers-day anniversary ready-made clear">
          <a href="flower_StarGazer/stargazer.php">
            <img src="https://down-ph.img.susercontent.com/file/ddf44abe0e2cd18cae9af60b1311c2e2" alt="Stargazer">
            <span>Stargazer <br> ₱1529</span>
          </a>
        </div>
      </div>

      <div class="no-results" style="display: none; text-align: center; font-size: 20px; color: #333333; font-family: 'BodyFont', sans-serif;">
        No products found matching your search.
      </div>
    </section>
  </main>

  <script>
    document.querySelector('.search-bar button').addEventListener('click', function () {
      const query = document.querySelector('.search-bar input').value.toLowerCase();
      const flowerItems = document.querySelectorAll('.flower-item');
      const noResultsMessage = document.querySelector('.no-results');
      let found = false;

      flowerItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(query)) {
          item.style.display = 'block';
          found = true;
        } else {
          item.style.display = 'none';
        }
      });

      noResultsMessage.style.display = found ? 'none' : 'block';
    });

    document.querySelector('.search-bar input').addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        document.querySelector('.search-bar button').click();
      }
    });

    function filterCategory(category) {
      const items = document.querySelectorAll('.flower-item');
      const noResultsMessage = document.querySelector('.no-results');
      let found = false;

      items.forEach(item => {
        if (category === 'clear') {
          item.style.display = 'block';
          found = true;
        } else if (item.classList.contains(category)) {
          item.style.display = 'block';
          found = true;
        } else {
          item.style.display = 'none';
        }
      });

      noResultsMessage.style.display = found ? 'none' : 'block';
    }
  </script>
  
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
  document.querySelectorAll(".dropdown-toggle").forEach(toggle => {
    toggle.addEventListener("click", () => {
      let content = toggle.nextElementSibling;
      content.classList.toggle("show");
    });
  });
</script>

<script>
  let activeFilters = [];

  function filterCategory(category) {
    const items = document.querySelectorAll('.flower-item');
    const noResultsMessage = document.querySelector('.no-results');
    const buttons = document.querySelectorAll('.category-group button');
    let found = false;

    if (category === 'clear') {

      activeFilters = [];
      items.forEach(item => item.style.display = 'block');
      buttons.forEach(btn => btn.classList.remove('active'));
      noResultsMessage.style.display = 'none';
      return;
    }

    if (activeFilters.includes(category)) {
      activeFilters = activeFilters.filter(f => f !== category);
    } else {
      activeFilters.push(category);
    }
	
    buttons.forEach(btn => {
      const btnCategory = btn.getAttribute("onclick").match(/'(.*?)'/)[1];
      if (activeFilters.includes(btnCategory)) {
        btn.classList.add("active");
      } else {
        btn.classList.remove("active");
      }
    });

    items.forEach(item => {
      if (activeFilters.length === 0) {
        item.style.display = 'block';
        found = true;
      } else if (activeFilters.every(f => item.classList.contains(f))) {
        item.style.display = 'block';
        found = true;
      } else {
        item.style.display = 'none';
      }
    });

    noResultsMessage.style.display = found ? 'none' : 'block';
  }
</script>
	
</body>
</html>
