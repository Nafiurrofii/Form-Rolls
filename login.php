<?php
// Start session securely
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false, // Set true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Form Roll</title>
  <link
    href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
    rel="stylesheet">
  <link rel="icon" type="image/png" href="assets/images/logo-ljp.png">

  <style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
        background-color: #f0f4f8;
        background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
        background-size: 24px 24px;
        font-family: 'DM Sans', sans-serif;
    }

    .login-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
        max-width: 420px;
        z-index: 10;
        padding: 20px;
        box-sizing: border-box;
    }

    .auth-card {
        background: #ffffff;
        width: 100%;
        border-radius: 24px;
        padding: 48px 40px;
        box-shadow: 0 20px 60px -10px rgba(37, 99, 235, 0.15), 0 10px 30px -15px rgba(37, 99, 235, 0.1);
        text-align: center;
        position: relative;
        box-sizing: border-box;
    }

    .auth-brand-icon {
        width: 80px;
        height: 80px;
        background: #ffffff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px auto;
        box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.1);
        position: relative;
    }

    .auth-brand-icon img {
        width: 60px;
        height: 60px;
        object-fit: contain;
    }

    .auth-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }

    .auth-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0 0 24px 0;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 24px 0;
        position: relative;
    }

    .auth-divider::before {
        content: '';
        height: 1px;
        background: #e2e8f0;
        width: 60%;
        position: absolute;
        z-index: 1;
    }

    .auth-divider-line {
        width: 32px;
        height: 3px;
        background: #2563eb;
        border-radius: 2px;
        position: relative;
        z-index: 2;
    }

    .auth-form {
        text-align: left;
    }

    .field-group {
        margin-bottom: 20px;
    }

    .field-label {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .field-label svg {
        width: 16px;
        height: 16px;
        color: #2563eb;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper input {
        width: 100%;
        height: 48px;
        padding-left: 44px;
        padding-right: 44px;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        font-size: 14px;
        color: #334155;
        transition: all 0.2s;
        background: #ffffff;
        box-sizing: border-box;
        font-family: 'DM Sans', sans-serif;
    }

    .input-wrapper input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    .input-icon {
        position: absolute;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 44px;
    }

    .input-icon.left {
        left: 0;
    }

    .input-icon.right {
        right: 0;
        background: none;
        border: none;
        cursor: pointer;
    }

    .input-icon.right:hover {
        color: #475569;
    }

    .input-icon svg {
        width: 20px;
        height: 20px;
    }

    .auth-btn {
        width: 100%;
        height: 52px;
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 700;
        font-size: 15px;
        letter-spacing: 0.05em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 8px 16px -4px rgba(37, 99, 235, 0.4);
        margin-top: 28px;
        font-family: 'DM Sans', sans-serif;
    }

    .auth-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -4px rgba(37, 99, 235, 0.5);
    }

    .auth-btn:active {
        transform: translateY(0);
    }

    .auth-btn svg {
        width: 20px;
        height: 20px;
        transform: rotate(180deg);
    }

    .security-badge {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 36px;
        position: relative;
    }

    .security-badge::before {
        content: '';
        height: 1px;
        background: #e2e8f0;
        width: 100%;
        position: absolute;
        z-index: 1;
    }

    .security-icon-wrap {
        background: #ffffff;
        padding: 0 12px;
        position: relative;
        z-index: 2;
        color: #2563eb;
    }

    .security-icon-wrap svg {
        width: 24px;
        height: 24px;
    }

    .security-text {
        font-size: 12px;
        color: #64748b;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .security-text svg {
        width: 14px;
        height: 14px;
    }

    .app-footer {
        margin-top: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: #475569;
    }

    .app-footer-icon {
        width: 32px;
        height: 32px;
        color: #1e293b;
    }

    .app-footer-text {
        text-align: left;
    }

    .app-footer-title {
        font-weight: 800;
        font-size: 14px;
        color: #0f172a;
        letter-spacing: 0.05em;
    }

    .app-footer-subtitle {
        font-size: 11px;
        color: #64748b;
    }

    .auth-error {
        background: #fef2f2;
        color: #dc2626;
        padding: 12px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 20px;
        display: none;
        text-align: center;
        border: 1px solid #fecaca;
    }

    .auth-error.show {
        display: block;
    }
    
    /* Login button arrow flip to match design */
    .auth-btn svg.login-arrow {
        transform: rotate(0);
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <div class="auth-card">
      <div class="auth-brand-icon">
        <img src="assets/images/logo-ljp.png" alt="Logo" style="width: 70px; height: auto;">
      </div>
      <h2 class="auth-title">Login</h2>
      <p class="auth-subtitle">Sistem Manajemen Produksi Roll</p>
      
      <div class="auth-divider">
        <div class="auth-divider-line"></div>
      </div>
      
      <div class="auth-error" id="authError">Username atau password salah.</div>
      
      <form class="auth-form" id="authForm" onsubmit="event.preventDefault();">
        <div class="field-group">
          <label class="field-label">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            Username
          </label>
          <div class="input-wrapper">
            <span class="input-icon left">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </span>
            <input type="text" id="authUsername" placeholder="Masukkan username" required autocomplete="username">
          </div>
        </div>
        
        <div class="field-group">
          <label class="field-label">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            Password
          </label>
          <div class="input-wrapper">
            <span class="input-icon left">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            </span>
            <input type="password" id="authPassword" placeholder="Masukkan password" required autocomplete="current-password">
            <button type="button" class="input-icon right toggle-password" id="loginTogglePassword" aria-label="Toggle password visibility">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="auth-btn" id="authSubmitBtn">
          <svg class="login-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
          MASUK
        </button>
      </form>

      <div class="security-badge">
        <div class="security-icon-wrap">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
        </div>
      </div>
      <div class="security-text">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        Akses sistem dilindungi dengan keamanan tingkat tinggi
      </div>
    </div>

    <div class="app-footer">
      <svg class="app-footer-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
      </svg>
      <div class="app-footer-text">
        <div class="app-footer-title">FORM ROLL</div>
        <div class="app-footer-subtitle">Production Management System</div>
      </div>
    </div>
  </div>

  <script type="module">
    import { initAuthUI } from './assets/js/modules/auth.js';
    
    document.addEventListener('DOMContentLoaded', () => {
        initAuthUI(() => {
            window.location.href = 'index.php';
        });

        // Toggle password visibility
        const toggleBtn = document.getElementById('loginTogglePassword');
        const passInput = document.getElementById('authPassword');
        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', () => {
                if (passInput.type === 'password') {
                    passInput.type = 'text';
                    toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>';
                } else {
                    passInput.type = 'password';
                    toggleBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                }
            });
        }
    });
  </script>
</body>

</html>
