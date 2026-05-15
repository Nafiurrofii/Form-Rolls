import { showNotification } from '../utils.js';
import { setCurrentUser, getCurrentUser } from '../state.js';

const API_URL = (() => {
  const pathArray = window.location.pathname.split('/');
  const projectIndex = pathArray.indexOf('Form-Roll');
  const basePath = projectIndex !== -1 ? pathArray.slice(0, projectIndex + 1).join('/') : '';
  return `${window.location.protocol}//${window.location.host}${basePath}/backend/roll.php`;
})();

export async function checkSession() {
  try {
    const response = await fetch(`${API_URL}?action=session`);
    if (response.ok) {
      const result = await response.json();
      if (result.status === 'success') {
        setCurrentUser(result.user);
        return result.user;
      }
    }
    return null;
  } catch (error) {
    console.error('Session check error:', error);
    return null;
  }
}

export async function login(username, password) {
  try {
    const response = await fetch(`${API_URL}?action=login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username, password })
    });
    
    const result = await response.json();
    if (response.ok && result.status === 'success') {
      setCurrentUser(result.user);
      return { success: true, user: result.user };
    }
    return { success: false, message: result.message || 'Login failed' };
  } catch (error) {
    console.error('Login error:', error);
    return { success: false, message: 'Koneksi error' };
  }
}

export async function logout() {
  try {
    await fetch(`${API_URL}?action=logout`);
    setCurrentUser(null);
    window.location.href = 'login.php';
  } catch (error) {
    console.error('Logout error:', error);
  }
}

export async function verifyAdmin(username, password) {
  try {
    const response = await fetch(`${API_URL}?action=verify_admin`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ username, password })
    });
    
    const result = await response.json();
    if (response.ok && result.status === 'success') {
      return { success: true };
    }
    return { success: false, message: result.message || 'Verifikasi gagal' };
  } catch (error) {
    console.error('Verify admin error:', error);
    return { success: false, message: 'Koneksi error' };
  }
}

// UI Handlers

export function initAuthUI(onLoginSuccess) {
  const authForm = document.getElementById('authForm');
  const authError = document.getElementById('authError');
  const btnLogout = document.getElementById('btnLogout');

  if (authForm) {
    authForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const username = document.getElementById('authUsername').value;
      const password = document.getElementById('authPassword').value;
      const submitBtn = document.getElementById('authSubmitBtn');
      
      submitBtn.disabled = true;
      submitBtn.textContent = 'Memeriksa...';
      
      const res = await login(username, password);
      if (res.success) {
        authError.classList.remove('show');
        if (onLoginSuccess) onLoginSuccess();
      } else {
        authError.textContent = res.message;
        authError.classList.add('show');
      }
      
      submitBtn.disabled = false;
      submitBtn.textContent = 'Masuk';
    });
  }

  if (btnLogout) {
    btnLogout.addEventListener('click', async () => {
      if (confirm('Yakin ingin logout?')) {
        await logout();
      }
    });
  }
}



export function updateUserBadge(user) {
  const badge = document.getElementById('topbarUserBadge');
  const avatar = document.getElementById('topbarUserAvatar');
  const name = document.getElementById('topbarUserName');
  const role = document.getElementById('topbarUserRole');

  if (user) {
    badge.style.display = 'flex';
    name.textContent = user.username;
    role.textContent = user.role;
    avatar.textContent = user.username.charAt(0).toUpperCase();
  } else {
    badge.style.display = 'none';
  }
}

// Admin Verification Flow
let resolveAdminVerify = null;

export function showAdminVerification() {
  return new Promise((resolve) => {
    resolveAdminVerify = resolve;
    const modal = document.getElementById('adminVerifyModal');
    const form = document.getElementById('avForm');
    const errorEl = document.getElementById('avError');
    const cancelBtn = document.getElementById('avCancelBtn');
    const closeTopBtn = document.getElementById('avCloseTopBtn');
    const togglePasswordBtn = document.getElementById('avTogglePassword');
    const passwordInput = document.getElementById('avPassword');
    
    // Reset form
    form.reset();
    errorEl.classList.remove('show');
    passwordInput.type = 'password'; // Reset to hidden
    modal.classList.add('show');
    document.getElementById('avUsername').focus();

    // Attach one-time events
    const submitHandler = async (e) => {
      e.preventDefault();
      const u = document.getElementById('avUsername').value;
      const p = document.getElementById('avPassword').value;
      const btn = document.getElementById('avSubmitBtn');
      
      btn.disabled = true;
      btn.textContent = 'Memeriksa...';
      
      const res = await verifyAdmin(u, p);
      if (res.success) {
        modal.classList.remove('show');
        cleanup();
        resolve(true);
      } else {
        errorEl.textContent = res.message;
        errorEl.classList.add('show');
      }
      
      btn.disabled = false;
      btn.textContent = 'Verifikasi';
    };

    const cancelHandler = () => {
      modal.classList.remove('show');
      cleanup();
      resolve(false);
    };

    const togglePasswordHandler = () => {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
      } else {
        passwordInput.type = 'password';
      }
    };

    const cleanup = () => {
      form.removeEventListener('submit', submitHandler);
      cancelBtn.removeEventListener('click', cancelHandler);
      if (closeTopBtn) closeTopBtn.removeEventListener('click', cancelHandler);
      if (togglePasswordBtn) togglePasswordBtn.removeEventListener('click', togglePasswordHandler);
    };

    form.addEventListener('submit', submitHandler);
    cancelBtn.addEventListener('click', cancelHandler);
    if (closeTopBtn) closeTopBtn.addEventListener('click', cancelHandler);
    if (togglePasswordBtn) togglePasswordBtn.addEventListener('click', togglePasswordHandler);
  });
}
