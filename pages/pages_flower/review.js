// review.js — replacement (drop into the same path you include it from)
(function(){
  // --- Helpers ---
  function getUsernameDynamic() {
    // 1) window.currentUser (server-injected)
    try {
      if (window.currentUser && window.currentUser.username) return window.currentUser.username;
    } catch(e){}

    // 2) body data attribute
    try {
      if (document.body && document.body.dataset && document.body.dataset.username) return document.body.dataset.username;
    } catch(e){}

    // 3) common localStorage/sessionStorage keys
    try {
      const keys = ['currentUser','user','userData','loggedUser','loggedInUser','sessionUser','account','username'];
      for (const k of keys) {
        const raw = sessionStorage.getItem(k) || localStorage.getItem(k);
        if (!raw) continue;
        try {
          const parsed = JSON.parse(raw);
          if (parsed?.username) return parsed.username;
          if (parsed?.user) return parsed.user;
        } catch {
          if (typeof raw === 'string' && raw.trim()) return raw.trim();
        }
      }
    } catch(e){}

    // 4) last resort: look for 'user-<name>' key used by your app for profilePic
    try {
      for (const k of Object.keys(localStorage)) {
        if (k.startsWith('user-')) {
          const parsed = JSON.parse(localStorage.getItem(k) || '{}');
          if (parsed?.username) return parsed.username;
        }
      }
    } catch(e){}

    return null;
  }

  // --- DOM refs ---
  const productTitle = document.querySelector('.product-title')?.textContent?.trim() || 'product';
  const reviewList = document.getElementById('review-list');
  const reviewText = document.getElementById('review-text');
  const reviewImageInput = document.getElementById('review-image');
  const starSpans = document.querySelectorAll('.star-rating span');
  const submitBtn = document.getElementById('submit-review');

  let selectedRating = 0;
  let editingIndex = null;

  function requireLogin() {
    alert('Please log in first to leave a review.');
    window.location.href = '../../pages_account/login.html';
  }

  // star UI
  starSpans.forEach(s => {
    s.addEventListener('click', () => {
      selectedRating = +s.dataset.value;
      starSpans.forEach(x => x.style.color = (+x.dataset.value <= selectedRating ? '#EE63BB' : '#333'));
    });
  });

  // load reviews
  function loadReviews(){
    const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`)) || [];
    reviewList.innerHTML = '';
    arr.forEach((r,i)=>{
      const imgTag = r.profilePic ? `<img src="${r.profilePic}" class="reviewer-profile-pic" alt="${r.user}">` : '';
      const reviewImageTag = r.reviewImage ? `<img src="${r.reviewImage}" class="review-photo" alt="Review image by ${r.user}">` : '';
      const usernameNow = getUsernameDynamic();
      const isOwner = usernameNow && (r.user === usernameNow);
      const delBtn = isOwner ? `<button data-index="${i}" class="delete-review">Delete</button>` : '';
      const editBtn = isOwner ? `<button data-index="${i}" class="edit-review">Edit</button>` : '';
      const editedFlag = r.edited ? '<span class="edited-flag">(This review was edited)</span>' : '';
      const entry = document.createElement('div');
      entry.className = 'review-entry';
      entry.innerHTML = `
        <div class="individual-review">
          ${imgTag}
          <p><strong>${r.user}</strong> ${'★'.repeat(r.rating)}<br>${r.text} ${editedFlag}</p>
          ${reviewImageTag}
          <div class="review-actions">${delBtn} ${editBtn}</div>
        </div>`;
      reviewList.appendChild(entry);
    });

    // attach handlers (delegate-ish)
    document.querySelectorAll('.delete-review').forEach(btn=>{
      btn.onclick = e=>{
        const idx = +e.target.dataset.index;
        const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`)) || [];
        arr.splice(idx,1);
        localStorage.setItem(`reviews-${productTitle}`, JSON.stringify(arr));
        loadReviews();
      };
    });
    document.querySelectorAll('.edit-review').forEach(btn=>{
      btn.onclick = e=>{
        const idx = +e.target.dataset.index;
        const arr = JSON.parse(localStorage.getItem(`reviews-${productTitle}`)) || [];
        const r = arr[idx];
        if (!r) return;
        reviewText.value = r.text || '';
        selectedRating = r.rating || 0;
        editingIndex = idx;
        starSpans.forEach(x => x.style.color = (+x.dataset.value <= selectedRating ? '#EE63BB' : '#333'));
        submitBtn.textContent = 'Update Review';
      };
    });
  }

  // file helper
  function fileToDataURL(file){
    return new Promise((resolve,reject)=>{
      if(!file) return resolve('');
      const reader = new FileReader();
      reader.onload = e=> resolve(e.target.result);
      reader.onerror = err=> reject(err);
      reader.readAsDataURL(file);
    });
  }

  // submit handler — re-check username at submission time (fixes race)
  submitBtn.addEventListener('click', async ()=> {
  const username = getUsernameDynamic();
  if (!username) return requireLogin();

  const text = (reviewText.value || '').trim();
  if (!text) return alert('Please enter review text.');
  if (selectedRating === 0) return alert('Please choose a star rating.');

  const file = reviewImageInput.files[0];
  let dataUrl = '';
  if (file) {
    try { dataUrl = await fileToDataURL(file); } catch(e){}
  }

  const reviewData = {
    user: username,
    rating: selectedRating,
    text,
    reviewImage: dataUrl
  };

  // send to PHP instead of localStorage
  fetch('daisybouquet.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(reviewData)
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      reviewText.value = '';
      selectedRating = 0;
      starSpans.forEach(x => x.style.color = '#333');
      loadReviewsFromServer(); // new loader
    } else {
      alert('Error saving review.');
    }
  })
  .catch(err => console.error('Submit failed:', err));
});

// new function: load reviews from PHP
function loadReviewsFromServer() {
  fetch('daisybouquet.php')
    .then(r => r.json())
    .then(arr => {
      reviewList.innerHTML = '';
      arr.forEach(r => {
        const entry = document.createElement('div');
        entry.className = 'review-entry';
        entry.innerHTML = `
          <div class="individual-review">
            <p><strong>${r.user}</strong> ${'★'.repeat(r.rating)}<br>${r.text}</p>
            ${r.reviewImage ? `<img src="${r.reviewImage}" class="review-photo">` : ''}
          </div>`;
        reviewList.appendChild(entry);
      });
    });
}

// auto-load reviews when page opens
loadReviewsFromServer();