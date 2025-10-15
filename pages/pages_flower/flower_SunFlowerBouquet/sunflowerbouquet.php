<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sunflower Bouquet - True Blossom Mart</title>
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
        <img src="https://www.tinsflowershop.com/3784-large_default/sunflower-sunshine-bouquet.jpg" alt="Rose Bunch">
      </div>
      <div class="product-details">
        <h1 class="product-title">SUNFLOWER BOUQUET</h1>
        <div class="rating">★★★★☆ <span>4.0%</span></div>
        <div class="events">
          <span>Birthday</span>
          <span>Wedding</span>
        </div>
        <div class="price">₱1,400</div>

        <div class="quantity">
          <label>Quantity:</label>
          <button id="decrease">-</button>
          <input type="number" id="quantityInput" value="1" min="1">
          <button id="increase">+</button>
        </div>

        <div class="description">
          <h3>DETAILS</h3>
          <p>
            A striking sunflower bouquet, celebrated for its bold, sunny blooms that add a burst of color to any room. Ideal for brightening up both indoor and outdoor spaces,
             this bouquet embodies warmth and joy. With its vibrant presence, it’s the perfect gift to express appreciation or a delightful centerpiece for your home or garden.
          </p>
        </div>
		
	<div class="action-buttons">
          <button class="buy-now">Buy Now</button>
          <button class="add-cart">Add to Cart</button>
          <button class="wishlist">Add to Wishlist</button>
        </div>
      </div>

      <div class="reviews">
        <h3>Reviews</h3>
        <div class="review-entry">
		<div class="individual-review">
			<img src="../../../assets/users/user_JG.jpg" class="reviewer-profile-pic" alt="Jimmy Gino">
			<p><strong>Jimmy Gino</strong> ★★★★★<br>My grandma who just turned 75 absolutely loved this!</p>
		</div>
		</div>
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
    const productName = "SUNFLOWER BOUQUET";
    const productPrice = "₱1,400";
    const productImageUrl = document.querySelector('.product-image img').src;
  
   const wishlistButton = document.querySelector('.wishlist');
  const addCartButton  = document.querySelector('.add-cart');
  const buyNowButton   = document.querySelector('.buy-now');
  const quantityInput  = document.getElementById('quantityInput');
  const decreaseBtn    = document.getElementById('decrease');
  const increaseBtn    = document.getElementById('increase');

  function isUserLoggedIn() { return sessionStorage.getItem('loggedIn') === 'true'; }
  function getQuantity()      { return parseInt(quantityInput.value, 10); }
  function showLoginAlert(m)  { alert(m); window.location.href = '../../pages_account/login.html'; }
  function addToStorageUserCart(item) {
  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username;
  if (!username) {
    alert('Please log in first.');
    window.location.href = '../../pages_account/login.html';
    return;
  }
  const userCartKey = `cart_${username}`;
  const items = JSON.parse(localStorage.getItem(userCartKey)) || [];
  const existing = items.find(x => x.name === item.name);

  if (existing) {
    existing.quantity += item.quantity;
    alert(`Updated quantity in your cart.`);
  } else {
    items.push(item);
    alert(`Product added to your cart!`);
  }

  localStorage.setItem(userCartKey, JSON.stringify(items));
}


  decreaseBtn.onclick = () => { let v = getQuantity(); if (v>1) quantityInput.value = v-1; };
  increaseBtn.onclick = () => { quantityInput.value = getQuantity()+1; };

  wishlistButton.onclick = () => {
  const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

  const alreadyInWishlist = wishlist.some(item => item.name === productName);

  if (alreadyInWishlist) {
    alert('This item is already in your wishlist!');
  } else {
    wishlist.push({
      name: productName,
      price: productPrice,
      imageUrl: productImageUrl,
      quantity: getQuantity()
    });
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    alert('Product added to wishlist!');
  }
};

  addCartButton.onclick = () => {
  if (!isUserLoggedIn()) return showLoginAlert('Please log in first to add items to your cart!');
  addToStorageUserCart({ name: productName, price: productPrice, imageUrl: productImageUrl, quantity: getQuantity() });
};

  buyNowButton.onclick = () => {
  if (!isUserLoggedIn()) return showLoginAlert('Please log in first to proceed with the purchase!');
  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username;
  if (!username) {
    alert('Please log in first.');
    window.location.href = '../../pages_account/login.html';
    return;
  }
  const userCartKey = `cart_${username}`;
  const cart = JSON.parse(localStorage.getItem(userCartKey)) || [];
  if (!cart.find(x => x.name === productName)) {
    cart.push({ name: productName, price: productPrice, imageUrl: productImageUrl, quantity: getQuantity() });
    localStorage.setItem(userCartKey, JSON.stringify(cart));
    alert('Proceed to Checkout');
  } else alert('This product is already in your cart!');
  window.location.href = '../../pages_orders/checkout.html';
};



  const productTitle = document.querySelector('.product-title').textContent.trim();
  const reviewList   = document.getElementById('review-list');
  const reviewText   = document.getElementById('review-text');
  const starSpans    = document.querySelectorAll('.star-rating span');
  const submitBtn    = document.getElementById('submit-review');
  const currentUser  = JSON.parse(localStorage.getItem('currentUser'))||{};
  const username     = currentUser.username||'Guest';
  let selectedRating = 0;
  let editingIndex = null;
  
  
  function normalizeLeetspeak(text) {
  return text
    .toLowerCase()
    .replace(/[@4]/g, 'a')
    .replace(/[3]/g, 'e')
    .replace(/[1!|]/g, 'i')
    .replace(/[0]/g, 'o')
    .replace(/[5$]/g, 's')
    .replace(/[7]/g, 't')
    .replace(/[^a-z\s]/g, '')       
    .replace(/\s+/g, ' ')          
    .trim();                       
}

  const exceptions = [
  "passion",
  "passionate",
  "classic",
  "classical",
  "assistant",
  "assistance",
  "grass",
  "grassy",
  "glass",
  "glasses",
  "fantastic",
  "fantasy",
  "plastic",
  "plastics",
  "amazing",
  "amazingly",
  "titanic",
  "title",
  "pretty",
  "passport",
  "compassion",
  "compassionate",
  "professional",
  "massive",
  "passport",
  "assignment",
  "assigned",
  "grassland",
  "smartphone",     
  "classification",
  "passenger",
  "passengers",
  "classroom",
  "grassroots",
  "love",
  "it"
];

  const profanityBlocklist = [
  // English profanity
  "fuck","shit","damn","hell","crap","piss","bastard","bastards","bitch","bitches",
  "ass","asshole","arsehole","dick","dicks","cock","cockhead","cocksucker","cunt",
  "motherfucker","fucker","prick","shithead","wanker","twat","bollocks",
  "boob","boobs","tits","tit","pussy","penis","penishead","dildo","vagina",
  "whore","slut","skank","cumdumpster","tart",
  "faggot","fag","dyke","queer","queermo","tranny","trannie","shemale",
  "nigger","nigga","spic","chink","gook","kike","coon","wetback","raghead","paki",
  "honky","cracker","redneck","sandnigger","zipperhead","hebe",
  "retard","retarded","moron","idiot","imbecile","dumbass","dunce","spastic",
  "shitface","skanky","asswipe","arsewipe","dickhead","fuckface","shitbag","cockface",
  
  // Tagalog profanity
  "putangina","putang ina","putangina mo","putang ina mo","tangina","tangina mo","tang ina",
  "pakshet","pakshet ka","tae","taena","tae na","buwisit","bwisit","leche",
  "gago","gaga","tanga","bugok","bobo","tarantado","tarantadong","ulol","sira ulo","tonto",
  "kulupad","kupal","wakal","hinayupak","kunti ka lang","ungas","hudas",
  "walang hiya","walang kwenta","walang kwenta ka",
  "burat","pekpek","puwet","inutil","pu**ta"
];

function containsProfanity(text) {
  const normalized = normalizeLeetspeak(text);
  const words = normalized.split(' ');

  for (const exception of exceptions) {
    if (words.includes(exception)) {
      return false;
    }
  }

  return words.some(word => profanityBlocklist.includes(word));
}

  starSpans.forEach(s => s.onclick = ()=>{
    selectedRating = +s.dataset.value;
    starSpans.forEach(x=> x.style.color = (+x.dataset.value<=selectedRating ? '#EE63BB':'#333'));
  });

  function loadReviews() {
    const reviews = JSON.parse(localStorage.getItem(`reviews-${productTitle}`))||[];
    reviewList.innerHTML = '';
    reviews.forEach((r,i)=>{
      const imgTag = r.profilePic
        ? `<img src="${r.profilePic}" class="reviewer-profile-pic" alt="${r.user}">`
        : '';
		const reviewImageTag = r.reviewImage
		? `<img src="${r.reviewImage}" class="review-photo" alt="Review image by ${r.user}">`
		: '';
      const isOwner = r.user === username;
		const delBtn = isOwner ? `<button data-index="${i}" class="delete-review">Delete</button>` : '';
		const editBtn = isOwner ? `<button data-index="${i}" class="edit-review">Edit</button>` : '';

      reviewList.insertAdjacentHTML('beforeend', `
        <div class="review-entry">
          <div class="individual-review">
            ${imgTag}
            <p>
			<strong>${r.user}</strong> ${'★'.repeat(r.rating)}<br>
			${r.text}
			${r.edited ? '<span class="edited-flag">(This review was edited)</span>' : ''}
			</p>
            ${delBtn} ${editBtn} 
			${reviewImageTag}
          </div>
        </div>
      `);
    });
    document.querySelectorAll('.delete-review').forEach(b=>{
      b.onclick = e=>{
        const idx = +e.target.dataset.index;
        const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`))||[];
        arr.splice(idx,1);
        localStorage.setItem(`reviews-${productTitle}`, JSON.stringify(arr));
        loadReviews();
      };
    });
	
	

	document.querySelectorAll('.edit-review').forEach(b => {
	b.onclick = e => {
    const idx = +e.target.dataset.index;
    const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`)) || [];
    const review = arr[idx];

    reviewText.value = review.text;
    selectedRating = review.rating;
    editingIndex = idx;

    starSpans.forEach(x => {
      x.style.color = (+x.dataset.value <= selectedRating ? '#EE63BB' : '#333');
    });

    submitBtn.textContent = "Update Review";
  };
});

  }

  const reviewImageInput = document.getElementById('review-image');
  submitBtn.onclick = () => {
  if (!isUserLoggedIn()) return showLoginAlert('Please log in first to leave a review!');
  const text = reviewText.value.trim();
  if (!text || selectedRating === 0) return alert('Enter text & select a rating.');
  if (containsProfanity(text)) {
    alert('Your review contains inappropriate language and cannot be submitted.');
    return;
  }

  const userKey = `user-${username}`;
  const userData = JSON.parse(localStorage.getItem(userKey)) || {};
  const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`)) || [];

  const file = reviewImageInput.files[0];
  const reader = new FileReader();

  reader.onload = function(e) {
    const updatedReview = {
      user: username,
      rating: selectedRating,
      text: text,
      profilePic: userData.profilePic || '',
      reviewImage: e.target.result || ''
    };

    if (editingIndex !== null) {
      updatedReview.edited = true;
      arr[editingIndex] = updatedReview;
      editingIndex = null;
      submitBtn.textContent = "Submit Review";
    } else {
      arr.push(updatedReview);
    }

    localStorage.setItem(`reviews-${productTitle}`, JSON.stringify(arr));
    reviewText.value = '';
    reviewImageInput.value = '';
    selectedRating = 0;
    starSpans.forEach(x => x.style.color = '#333');
    loadReviews();
  };

  if (file) {
    reader.readAsDataURL(file);
  } else {
    reader.onload({ target: { result: '' } });
  }
};


  loadReviews();
</script>

  <script defer src="../review.js"></script>
  
  <script>
	function updateCartCount() {
  const cartCountElement = document.getElementById('cart-count');
  if (!cartCountElement) return;

  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username || null;
  if (!username) {
    cartCountElement.textContent = '0';
    return;
  }

  const userCartKey = `cart_${username}`;
  const cart = JSON.parse(localStorage.getItem(userCartKey)) || [];
  const count = cart.reduce((sum, item) => sum + item.quantity, 0);
  cartCountElement.textContent = count;
}

document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
});

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
