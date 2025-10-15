<?php
$reviewsFile = __DIR__ . "/reviews.json";

// detect API call (AJAX fetch)
$isApi = (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
    || (isset($_GET['ajax']) && $_GET['ajax'] == '1')
);

if ($isApi) {
    header("Content-Type: application/json");
    $reviews = [];
    if (file_exists($reviewsFile)) {
        $reviews = json_decode(file_get_contents($reviewsFile), true) ?: [];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);
        if ($input && isset($input['user'], $input['rating'], $input['text'])) {
            $reviews[] = [
                "user" => htmlspecialchars($input['user']),
                "rating" => intval($input['rating']),
                "text" => htmlspecialchars($input['text']),
                "reviewImage" => $input['reviewImage'] ?? ""
            ];
            file_put_contents($reviewsFile, json_encode($reviews, JSON_PRETTY_PRINT));
            echo json_encode(["success" => true]);
            exit;
        } else {
            echo json_encode(["success" => false, "error" => "Invalid input"]);
            exit;
        }
    }

    echo json_encode($reviews);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daisy Bouquet - True Blossom Mart</title>
  <link rel="stylesheet" href="../universalflowerstyle.css">
</head>
<body>
  <header class="tbmHeader">
    <div class="dropdown">
        <button class="dropbtn">
            <img src="../../../assets/images/dropbtn_Logo.png" alt="DropDown Button">
        </button>
        <div class="dropdown-content">
          <div class="smalllogo">
            <img src="../../../assets/images/LogoSmall.png" alt="TBMart Small">
          </div>
          <a href="../../../homepage.php">HOME</a>
          <a href="../flowers.php">PRODUCTS</a>
          <a href="../../pages_about/about.php">ABOUT US</a>
          <a href="../../pages_orders/addtocart.php" class="orders-link">ORDERS<span id="cart-count" class="cart-count">0</span></a>
          <a href="../../pages_account/acc.php" id="accountLink">ACCOUNT</a>
        </div>
    </div>
    <div class="logo">
      <a href="../../../homepage.php"><img src="../../../assets/images/LogoMain.png" alt="TBMart Logo"></a>
    </div>
  </header>

  <main class="product-container">
    <div class="back-button">
      <a href="../flowers.php">← BACK</a>
    </div>

    <div class="product-card">
      <div class="product-image">
        <img src="https://asset.bloomnation.com/c_pad,d_vendor:global:catalog:product:image.png,f_auto,fl_preserve_transparency,q_auto/v1660190201/vendor/6167/catalog/product/2/0/20220613100522_file_62a7b4a2686c9_62a7b54400d97.jpeg" alt="Daisy Bouquet">
      </div>

      <div class="product-details">
        <h1 class="product-title">DAISY BOUQUET</h1>
        <div class="rating">★★★★★ <span>5.0</span></div>
        <div class="events">
          <span>Anniversary</span>
          <span>Mother's Day</span>
          <span>Birthday</span>
        </div>
        <div class="price">₱1,350</div>

        <div class="quantity">
          <label>Quantity:</label>
          <button id="decrease">-</button>
          <input type="number" id="quantityInput" value="1" min="1">
          <button id="increase">+</button>
        </div>

        <div class="description">
          <h3>DETAILS</h3>
          <p>
            This daisy bouquet offers a sweet and loving tribute. With its bright, cheerful blooms,
            it brings a sense of warmth and happiness to any space. Thoughtfully arranged, it’s a heartfelt way to honor someone special—offering comfort, joy, and a gentle reminder of the love they brought into our lives.
          </p>
        </div>

        <div class="action-buttons">
         <form method="post" action="../../pages_orders/addtocart.php">
            <input type="hidden" name="add" value="DAISY BOUQUET">
            <input type="hidden" name="price" value="1350">
            <input type="hidden" name="imageUrl" value="https://asset.bloomnation.com/c_pad,d_vendor:global:catalog:product:image.png,f_auto,fl_preserve_transparency,q_auto/v1660190201/vendor/6167/catalog/product/2/0/20220613100522_file_62a7b4a2686c9_62a7b54400d97.jpeg">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="add-btn">Add to Cart</button>
         </form>
          <form method="post" action="../../pages_account/wishlist.php">
            <input type="hidden" name="add" value="DAISY BOUQUET">
            <input type="hidden" name="price" value="₱1,350">
            <input type="hidden" name="imageUrl" value="https://asset.bloomnation.com/c_pad,d_vendor:global:catalog:product:image.png,f_auto,fl_preserve_transparency,q_auto/v1660190201/vendor/6167/catalog/product/2/0/20220613100522_file_62a7b4a2686c9_62a7b54400d97.jpeg">
            <input type="hidden" name="quantity" id="wishlistQuantity" value="1">
            <button type="submit" class="wishlist">Add to Wishlist</button>
          </form>
        </div>
      </div>

      <div class="reviews">
        <h3>Reviews</h3>
        <div id="review-list"></div>
        <div class="review-form">
          <input type="text" id="review-text" placeholder="Share your thoughts..." />
          <input type="file" id="review-image" accept="image/*" />
          <div class="star-rating">
            <span data-value="1">★</span>
            <span data-value="2">★</span>
            <span data-value="3">★</span>
            <span data-value="4">★</span>
            <span data-value="5">★</span>
          </div>
        </div>
        <button id="submit-review">Submit Review</button>
      </div>
    </div>
  </main>

  <script>
  const productTitle = document.querySelector('.product-title').textContent.trim();
  const reviewList   = document.getElementById('review-list');
  const reviewText   = document.getElementById('review-text');
  const reviewImageInput = document.getElementById('review-image');
  const starSpans    = document.querySelectorAll('.star-rating span');
  const submitBtn    = document.getElementById('submit-review');
  const currentUser  = JSON.parse(localStorage.getItem('currentUser'))||{};
  const username     = currentUser.username||'Guest';
  let selectedRating = 0;

  function isUserLoggedIn() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
    return !!currentUser.username; // true if username exists
  }
  function showLoginAlert(m)  { alert(m); window.location.href = '../../pages_account/login.html'; }

  // star rating click
  starSpans.forEach(s => s.onclick = ()=> {
    selectedRating = +s.dataset.value;
    starSpans.forEach(x => x.style.color = (+x.dataset.value <= selectedRating ? '#EE63BB':'#333'));
  });

  function loadReviews() {
    fetch('daisybouquet.php?ajax=1')
      .then(r => r.json())
      .then(reviews => {
        reviewList.innerHTML = '';
        reviews.forEach(r => {
          const reviewImageTag = r.reviewImage
            ? `<img src="${r.reviewImage}" class="review-photo" alt="Review image by ${r.user}">`
            : '';
          reviewList.insertAdjacentHTML('beforeend', `
            <div class="review-entry">
              <div class="individual-review">
                <p><strong>${r.user}</strong> ${'★'.repeat(r.rating)}<br>${r.text}</p>
                ${reviewImageTag}
              </div>
            </div>
          `);
        });
      });
  }

  submitBtn.onclick = () => {
    if (!isUserLoggedIn()) return showLoginAlert('Please log in first to leave a review!');
    const text = reviewText.value.trim();
    if (!text || selectedRating === 0) return alert('Enter text & select a rating.');

    const file = reviewImageInput.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => sendReview(text, e.target.result);
      reader.readAsDataURL(file);
    } else {
      sendReview(text, '');
    }
  };

  function sendReview(text, reviewImage) {
    const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
    const username = currentUser.username || 'Guest';

    fetch('daisybouquet.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user: username, rating: selectedRating, text, reviewImage })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        reviewText.value = '';
        reviewImageInput.value = '';
        selectedRating = 0;
        starSpans.forEach(x => x.style.color = '#333');
        loadReviews();
      } else {
        alert('Error: ' + (data.error || 'Unable to save review.'));
      }
    });
  }
  
  loadReviews();
  </script>
  
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
