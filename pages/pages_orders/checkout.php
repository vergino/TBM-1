<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout – TrueBlossomMart</title>
  <link rel="stylesheet" href="checkoutstyle.css">

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

  <div id="tncModal" class="modal-backdrop">
    <div class="modal-box">
      <h1>TERMS & CONDITIONS</h1>
      <div class="tnc-text">
        <p>Welcome to True Blossom Mart! These Terms and Conditions govern your use of our website and services...</p>
        <p>To access certain features of our website, you may be required to create an account...</p>
        <p>All orders placed on True Blossom Mart are subject to stock availability...</p>
        <p>Payments must be made using our specified methods. Payment details are handled securely by third parties...</p>
        <p>Orders must be canceled within a reasonable time frame before processing can start...</p>
        <p>Delivery times are estimated. True Blossom Mart cannot be held accountable for third-party shipping delays...</p>
        <p>We strive to maintain product quality. If you receive a wrong or defective product, inform us within 24 hours...</p>
        <p>You agree not to engage in fraudulent transactions or violate applicable laws...</p>
        <p>All content belongs to True Blossom Mart or its licensors. Unauthorized use is prohibited...</p>
        <p>Your use of the platform is subject to our Privacy Policy...</p>
        <p>We reserve the right to update these terms at any time. Continued use implies acceptance...</p>
        <p>For inquiries, contact <a href="mailto:support@trueblossommart.com">support@trueblossommart.com</a>.</p>
      </div>
      <button id="agreeBtn">I Agree</button>
      <button id="disagreeBtn">I Disagree</button>
    </div>
  </div>

  <div id="checkout">
    <div class="back-button">
      <a href="addtocart.html">← BACK</a>
    </div>

    <div class="checkout-container">

      <div class="section">
        <h1>RECEIVER INFORMATION</h1>

        <div class="profile-row">
          <label for="rName">NAME:</label>
          <input id="rName" type="text" placeholder="e.g. Juan Dela Cruz" required>
        </div>

        <div class="profile-row">
          <label for="rEmail">EMAIL:</label>
          <input id="rEmail" type="email" placeholder="sample@gmail.com" required>
        </div>

        <div class="profile-row">
          <label for="rContact">CONTACT NO.:</label>
          <input id="rContact" type="tel" placeholder="e.g. +63 912 345 6789" required pattern="\+63\s\d{3}\s\d{3}\s\d{4}">
        </div>

        <div class="profile-row">
          <label for="rRegion">REGION:</label>
          <input id="rRegion" type="text" required>
          <label for="rProvince">Province:</label>
          <input id="rProvince" type="text" required>
        </div>

        <div class="profile-row">
          <label for="rCity">CITY:</label>
          <input id="rCity" type="text" required>
          <label for="rPostal">Postal Code:</label>
          <input id="rPostal" type="text" required>
        </div>

        <div class="profile-row">
          <label for="rAddress">ADDRESS:</label>
          <input id="rAddress" type="text" placeholder="Street, Barangay…" required>
        </div>

        <div class="profile-row">
          <label for="rNote">NOTE:</label>
          <input id="rNote" type="text">
        </div>

        <div class="profile-row">
          <label for="rDate">DELIVERY DATE:</label>
          <input id="rDate" type="date" required>
        </div>
      </div>

      <div class="section">
        <h1>ORDER SUMMARY</h1>
        <div id="orderSummary"></div>

        <div class="order-totals">
          <p>Subtotal: ₱<span id="subtotal">0</span></p>
          <p>Shipping Fee: ₱<span id="shippingFee">50</span></p>
          <p id="sameDayFeeContainer" style="display: none;">Same Day Delivery Fee: ₱<span id="sameDayFee">40</span></p>
          <p><strong>Total: ₱<span id="total">0</span></strong></p>
        </div>

        <h3>PAYMENT METHOD</h3>
        <div class="radio-group" id="payMethods"></div>

        <h3>DELIVERY MODE</h3>
        <div class="btn-group" id="shipMethods"></div>

        <button class="btn-pay" id="payBtn">PAY</button>
      </div>
    </div>

    <div id="thankYouScreen" style="display:none; text-align:center; padding:50px;">
      <h1>Thank You for Your Order!</h1>
      <p>Your payment was successful and your flowers will be on their way shortly.</p>
    </div>
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
window.addEventListener('DOMContentLoaded', () => {
  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username || 'Guest';
  const accepted = localStorage.getItem(`tncAccepted_${username}`);
  const modal = document.getElementById('tncModal');

  if (accepted === 'true') {
    modal.remove();
  } else {
    modal.style.display = 'flex';
  }

  document.getElementById('agreeBtn').addEventListener('click', () => {
    localStorage.setItem(`tncAccepted_${username}`, 'true');
    modal.remove();
    window.location.href = 'checkout.html';
  });

  document.getElementById('disagreeBtn').addEventListener('click', () => {
    alert('You must accept the Terms and Conditions to continue.');
    window.location.href = 'addtocart.html';
  });
});

function renderOrder() {
  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username || 'Guest';
  const cartKey = `cart_${username}`;
  const cart = JSON.parse(localStorage.getItem(cartKey)) || [];

  const container = document.getElementById('orderSummary');
  container.innerHTML = '';
  let sub = 0;

  cart.forEach(item => {
    const price = parseFloat(item.price.replace(/[^\d.]/g, ''));
    sub += price * item.quantity;

    const div = document.createElement('div');
    div.className = 'order-item';
    div.innerHTML = `<img src="${item.imageUrl}" alt="${item.name}"><div>${item.name} ×${item.quantity}<br>₱${(price * item.quantity).toLocaleString()}</div>`;
    container.appendChild(div);
  });

  document.getElementById('subtotal').textContent = sub.toLocaleString();
  updateTotal();
}

['cod', 'ewallet'].forEach(m => {
  const b = document.createElement('button');
  b.textContent = m === 'cod' ? 'Cash on Delivery' : 'E-Wallet';
  b.dataset.method = m;
  document.getElementById('payMethods').appendChild(b);
});

['same', 'reservation'].forEach(s => {
  const b = document.createElement('button');
  b.textContent = s === 'same' ? 'Same Day' : 'Reservation';
  b.dataset.ship = s;
  document.getElementById('shipMethods').appendChild(b);
});

function setupToggle(id) {
  document.getElementById(id).querySelectorAll('button').forEach(b => {
    b.addEventListener('click', () => {
      document.getElementById(id).querySelectorAll('button').forEach(x => x.classList.remove('active'));
      b.classList.add('active');
    });
  });
}

setupToggle('payMethods');
setupToggle('shipMethods');

function updateTotal() {
  const sub = parseFloat(document.getElementById('subtotal').textContent.replace(/,/g, ''));
  const ship = 50;
  const sameDayExtra = document.getElementById('sameDayFeeContainer').style.display === 'flex' ? 40 : 0;
  const total = sub + ship + sameDayExtra;
  document.getElementById('total').textContent = total.toFixed(2);
}

document.getElementById('payBtn').addEventListener('click', () => {
  const name = document.getElementById('rName').value;
  const email = document.getElementById('rEmail').value;
  const contact = document.getElementById('rContact').value;
  const region = document.getElementById('rRegion').value;
  const province = document.getElementById('rProvince').value;
  const city = document.getElementById('rCity').value;
  const postal = document.getElementById('rPostal').value;
  const address = document.getElementById('rAddress').value;
  const date = document.getElementById('rDate').value;
  const selectedPayBtn = document.querySelector('#payMethods button.active');
  const paymentMethod = selectedPayBtn?.dataset.method || '';
  const selectedShipBtn = document.querySelector('#shipMethods button.active');
  const shippingMethod = selectedShipBtn?.dataset.ship || '';

  if (!name || !email || !contact || !region || !province || !city || !postal || !address) {
    return alert('Please fill in all required receiver details.');
  }

  if (shippingMethod === 'reservation' && !date) {
    return alert('Please fill in expected date of delivery.');
  }

  if (!paymentMethod) return alert('Please select the payment method.');
  if (!shippingMethod) return alert('Please select the shipping method.');

  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  const username = currentUser.username || 'Guest';
  const cartKey = `cart_${username}`;
  const cart = JSON.parse(localStorage.getItem(cartKey)) || [];

  if (cart.length === 0) {
    return alert('Your cart is empty. You cannot proceed with payment without purchasing anything.');
  }

  const orders = JSON.parse(localStorage.getItem('orders')) || [];

orders.push({
  id: Date.now(),
  name: name,
  email: email,
  contact: contact,
  region: region,
  province: province,
  city: city,
  postal: postal,
  address: address,
  date: new Date().toLocaleString(),
  expectedDate: shippingMethod === 'reservation' ? date : null,
  paymentMethod: paymentMethod,
  shippingMethod: shippingMethod,
  total: document.getElementById('total').textContent,
  items: cart
});


  localStorage.setItem('orders', JSON.stringify(orders));
  localStorage.removeItem(cartKey);

  const thankYouMessage = document.createElement('div');
  thankYouMessage.classList.add('thank-you-message');
  thankYouMessage.innerHTML = `
    <h1>Thank you, ${name}!</h1>
    <p>Your payment of ₱${document.getElementById('total').textContent} was successful.</p>
    <p>Your order will be processed and shipped soon.</p>
    <button id="continueBtn">Continue to Homepage</button>
  `;
  document.body.appendChild(thankYouMessage);

  document.getElementById('continueBtn').addEventListener('click', () => {
    window.location.href = '../../homepage.html';
  });
});

window.addEventListener('DOMContentLoaded', renderOrder);

window.addEventListener('DOMContentLoaded', () => {
  const dateRow = document.getElementById('rDate').closest('.profile-row');
  dateRow.style.display = 'none';

  document.querySelectorAll('#shipMethods button').forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.dataset.ship === 'reservation') {
        dateRow.style.display = 'flex';

        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        document.getElementById('rDate').setAttribute('min', minDate);
      } else {
        dateRow.style.display = 'none';
      }
    });
  });
});

document.querySelectorAll('#shipMethods button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.getElementById('shippingFee').textContent = '50';

    const extraFeeContainer = document.getElementById('sameDayFeeContainer');
    extraFeeContainer.style.display = btn.dataset.ship === 'same' ? 'flex' : 'none';

    updateTotal();
  });
});

(function() {
  const payBtn = document.getElementById('payBtn');
  if (!payBtn) return;

  payBtn.addEventListener('click', function(e) {
    const active = document.querySelector('#payMethods button.active');
    const method = active?.dataset.method;

    if (method === 'ewallet') {
      const currentUser = JSON.parse(localStorage.getItem('currentUser'));
      if (!currentUser) {
        alert('You must be logged in to pay via E-Wallet.');
        e.preventDefault();
        e.stopImmediatePropagation();
        return;
      }
      const userKey = `user-${currentUser.username}`;
      const data = JSON.parse(localStorage.getItem(userKey)) || {};

      const linkedMethod = data.linkedMethod;
      const linkedData = data.linkedData;
      const paymayaLinked = !!data.paymayaId && !!data.paymayaPwd;
      const gcashLinked = linkedMethod === 'gcash' && !!linkedData;

      let notLinkedMsg = '';
      if (linkedMethod === 'gcash' && !gcashLinked) {
        notLinkedMsg = 'You have not linked your GCash yet.';
      } else if (linkedMethod === 'paymaya' && !paymayaLinked) {
        notLinkedMsg = 'You have not linked your PayMaya yet.';
      } else if (!linkedMethod) {
        notLinkedMsg = 'You have not set up any E-Wallet in your Profile yet.';
      }

      if (notLinkedMsg) {
        e.preventDefault();
        e.stopImmediatePropagation();
        alert(`${notLinkedMsg}\nYou will be redirected to the Profile Page in order to link your account!`);
        window.location.href = '../../pages/pages_account/acc.html';
      }
    }
  }, true);
})();
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
