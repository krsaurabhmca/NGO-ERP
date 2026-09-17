<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <title><?php echo e($title ?? 'Login'); ?> - <?php echo !empty($globalSettings['ngo_name']) ? e($globalSettings['ngo_name']) : e(APP_NAME); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <?php if(!empty($globalSettings['ngo_favicon'])): ?>
    <link rel="icon" href="<?php echo file_url($globalSettings['ngo_favicon']); ?>" type="image/x-icon"/>
  <?php endif; ?>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f0f2f5;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
      padding: 0.75rem;
    }
    .login-wrapper {
      display: flex;
      width: 100%;
      max-width: 900px;
      min-height: 520px;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 12px rgba(0,0,0,0.06), 0 8px 40px rgba(0,0,0,0.04);
    }
    .login-brand {
      flex: 1;
      background-color: var(--primary); background-image: linear-gradient(115deg, var(--primary-dark) 0%, var(--primary-dark) 40%, transparent 40%), linear-gradient(35deg, transparent 60%, var(--accent) 60%, var(--accent) 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2.5rem;
      color: #fff;
      position: relative;
      overflow: hidden;
    }
    .login-brand::before {
      content: '';
      position: absolute;
      top: -60px;
      right: -60px;
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: rgba(255,191,0,0.06);
    }
    .login-brand::after {
      content: '';
      position: absolute;
      bottom: -80px;
      left: -40px;
      width: 250px;
      height: 250px;
      border-radius: 50%;
      background: rgba(13,148,136,0.06);
    }
    .login-brand .brand-icon {
      width: 72px;
      height: 72px;
      border-radius: 16px;
      background: rgba(255,191,0,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: var(--accent);
      margin-bottom: 1.25rem;
      position: relative;
      z-index: 1;
    }
    .login-brand img {
      max-height: 48px;
      margin-bottom: 1.25rem;
      position: relative;
      z-index: 1;
    }
    .login-brand h1 {
      font-size: 1.4rem;
      font-weight: 800;
      letter-spacing: -0.02em;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    .login-brand p {
      font-size: 0.82rem;
      opacity: 0.55;
      margin: 0.4rem 0 0;
      text-align: center;
      position: relative;
      z-index: 1;
    }
    .login-form {
      width: 420px;
      background: #fff;
      padding: 2.75rem 2.5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .login-form h2 {
      font-size: 1.35rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 0.25rem;
    }
    .login-form .subtitle {
      font-size: 0.85rem;
      color: #64748b;
      margin-bottom: 1.25rem;
    }
    .form-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: #475569;
      margin-bottom: 0.35rem;
    }
    .input-icon-wrap {
      position: relative;
    }
    .input-icon-wrap .input-icon {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 0.9rem;
      pointer-events: none;
      z-index: 2;
    }
    .input-icon-wrap .form-control {
      padding-left: 38px;
      border-radius: 8px;
      padding: 0.65rem 0.9rem 0.65rem 38px;
      border: 1.5px solid #e2e8f0;
      font-size: 0.9rem;
      transition: all 0.2s;
      background: #fff;
    }
    .input-icon-wrap .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(0,53,102,0.08);
    }
    .input-icon-wrap .toggle-password {
      position: absolute;
      right: 4px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #94a3b8;
      padding: 8px 10px;
      cursor: pointer;
      z-index: 2;
    }
    .input-icon-wrap .toggle-password:hover {
      color: var(--primary);
    }
    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }
    .btn-login {
      background: var(--primary);
      border: none;
      border-radius: 8px;
      padding: 0.7rem;
      font-weight: 600;
      font-size: 0.9rem;
      color: #fff;
      width: 100%;
      transition: all 0.2s;
    }
    .btn-login:hover {
      background: #00244d;
      color: #fff !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 16px rgba(0,53,102,0.25);
    }
    .btn-login:active {
      transform: translateY(0);
    }
    .alert {
      border-radius: 8px;
      font-size: 0.82rem;
      border: none;
      padding: 0.65rem 1rem;
    }
    .back-link {
      font-size: 0.82rem;
      color: #64748b;
      text-decoration: none;
      transition: color 0.2s;
    }
    .back-link:hover { color: var(--primary); }

    @media (max-width: 768px) {
      body { padding: 0; }
      .login-wrapper { flex-direction: column; max-width: 100%; min-height: auto; border-radius: 0; box-shadow: none; }
      .login-brand { padding: 1.5rem 1rem; min-height: auto; }
      .login-brand .brand-icon { width: 48px; height: 48px; font-size: 1.25rem; margin-bottom: 0.6rem; }
      .login-brand img { max-height: 36px; margin-bottom: 0.6rem; }
      .login-brand h1 { font-size: 1.05rem; }
      .login-brand p { font-size: 0.75rem; }
      .login-form { width: 100%; padding: 1.5rem 1rem; }
      .login-form h2 { font-size: 1.15rem; }
      .login-form .subtitle { font-size: 0.8rem; margin-bottom: 1rem; }

    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-brand">
      <?php if (!empty($globalSettings['ngo_logo'])): ?>
        <img src="<?php echo file_url($globalSettings['ngo_logo']); ?>" alt="Logo">
      <?php else: ?>
        <div class="brand-icon"><i class="fas fa-hand-holding-heart"></i></div>
      <?php endif; ?>
      <h1><?php echo !empty($globalSettings['ngo_name']) ? e($globalSettings['ngo_name']) : 'NGO HELP'; ?></h1>
      <p>Member &amp; Admin Login</p>
    </div>
    <div class="login-form">
      <h2>Sign In</h2>
      <p class="subtitle">Enter your credentials to access your account.</p>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center gap-2">
          <i class="fas fa-exclamation-circle"></i>
          <span><?php echo e($_SESSION['error']); unset($_SESSION['error']); ?></span>
        </div>
      <?php endif; ?>

      <?php if (!empty($locked)): ?>
        <div class="alert alert-warning d-flex align-items-center gap-2">
          <i class="fas fa-lock"></i>
          <span>Account temporarily locked due to too many failed attempts. Please try again in <?php echo (int)($lockout_minutes ?? 30); ?> minutes.</span>
        </div>
      <?php else: ?>

      <form action="<?php echo url('auth'); ?>" method="POST" autocomplete="off" novalidate>
        <?php echo csrf_field('auth/login'); ?>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <div class="input-icon-wrap">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Enter your email" autocomplete="off" required>
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label">Password</label>
          <div class="input-icon-wrap">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Enter your password" autocomplete="off" required>
            <button type="button" class="toggle-password" id="togglePassword" tabindex="-1">
              <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
          </div>
        </div>
        <?php if (!empty($needs_captcha) && !empty($captcha_question)): ?>
        <div class="mb-3">
          <label class="form-label">Security Check</label>
          <div class="input-icon-wrap">
            <i class="fas fa-calculator input-icon"></i>
            <input type="text" name="_captcha" class="form-control" placeholder="<?php echo e($captcha_question); ?> = ?" autocomplete="off" required>
          </div>
          <div class="mt-1" style="font-size:0.82rem;color:#64748b;">
            Solve: <strong><?php echo e($captcha_question); ?></strong>
          </div>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-login">
          <i class="fas fa-sign-in-alt me-2"></i> Sign In
        </button>
      </form>

      <?php endif; ?>



      <div class="text-center mt-2 pt-3 border-top">
        <a href="<?php echo url('/'); ?>" class="back-link">
          <i class="fas fa-arrow-left me-1"></i> Back to Home Page
        </a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const input = document.getElementById('loginPassword');
      const icon = document.getElementById('toggleIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });


  </script>
</body>
</html>
