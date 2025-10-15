// credential.js

function registerUser() {
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();
  const email    = document.getElementById('email').value.trim();
  const captcha  = document.getElementById('captchaCheck').checked;
  const errorMsg = document.getElementById('errorMsg');

  if (!username || !password || !email) {
    errorMsg.textContent = 'Please fill in all fields.';
    return;
  }
  const pwdRx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
  if (!pwdRx.test(password)) {
    errorMsg.textContent = 'Password must be 8+ chars with upper, lower, number.';
    return;
  }
  if (!captcha) {
    errorMsg.textContent = 'Please verify you are not a robot.';
    return;
  }

  const users = JSON.parse(localStorage.getItem('users')) || [];
  if (users.some(u => u.username === username)) {
    errorMsg.textContent = 'Username already exists.';
    return;
  }
  if (users.some(u => u.email === email)) {
    errorMsg.textContent = 'Email is already registered.';
    return;
  }

  // 1) add to master list
  const newUser = { username, password, email };
  users.push(newUser);
  localStorage.setItem('users', JSON.stringify(users));

  // 2) set currentUser & session
  localStorage.setItem('currentUser', JSON.stringify({ username, email }));
  sessionStorage.setItem('loggedIn', 'true');

  // 3) init per-user record if none
  const userKey = `user-${username}`;
  if (!localStorage.getItem(userKey))
    localStorage.setItem(userKey, JSON.stringify({ username, password, profilePic: '' }));

  alert('Account created! Please set your profile.');
  window.location.href = 'setprofile.html';
}

function loginUser() {
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();
  const errorMsg = document.getElementById('errorMsg');
  const users    = JSON.parse(localStorage.getItem('users')) || [];

  const found = users.find(u => u.username === username && u.password === password);
  if (!found) {
    errorMsg.textContent = 'Incorrect credentials';
    return;
  }

  sessionStorage.setItem('loggedIn', 'true');
  localStorage.setItem('currentUser', JSON.stringify({ username, email: found.email }));
  window.location.href = '../../homepage.html';
}

function resetPassword() {
  const email = document.getElementById('email').value.trim();
  const newPassword = document.getElementById('newPassword').value.trim();
  const confirmPassword = document.getElementById('confirmPassword').value.trim();
  const errorMsg = document.getElementById('errorMsg');
  const users = JSON.parse(localStorage.getItem('users')) || [];

  // Check if email exists
  const user = users.find(u => u.email === email);
  if (!user) {
    errorMsg.textContent = 'Email not registered.';
    return;
  }

  // Validate new password
  if (!newPassword || newPassword !== confirmPassword) {
    errorMsg.textContent = 'Passwords do not match or are empty.';
    return;
  }
  const pwdRx = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
  if (!pwdRx.test(newPassword)) {
    errorMsg.textContent = 'Password must be 8+ chars with upper, lower, number.';
    return;
  }

  // Update password in the users array
  user.password = newPassword;
  localStorage.setItem('users', JSON.stringify(users));

  alert('Password successfully updated!');
  window.location.href = 'login.html';  // Redirect to login page after reset
}

function showProfile() {
  const currentUser = JSON.parse(localStorage.getItem('currentUser')) || {};
  document.getElementById('profileUsername').textContent = currentUser.username || 'Guest';
  // Add any other profile-related information here
}

function logoutUser() {
  sessionStorage.removeItem('loggedIn');
  localStorage.removeItem('currentUser');
  alert('You have logged out.');
  window.location.href = '../../homepage.html';
}
