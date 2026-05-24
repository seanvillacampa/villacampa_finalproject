<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Farm Supply Management System</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-dark:   #1a3a1f;
      --green-mid:    #2d6a35;
      --green-accent: #4caf60;
      --gold:         #c9a84c;
      --cream:        #f5f0e8;
      --white:        #ffffff;
      --glass-bg:     rgba(10, 25, 12, 0.55);
      --glass-border: rgba(255, 255, 255, 0.12);
      --input-bg:     rgba(255, 255, 255, 0.08);
      --input-border: rgba(255, 255, 255, 0.2);
      --shadow:       0 32px 64px rgba(0,0,0,0.45);
      --radius:       16px;
    }

    html, body {
      height: 100%;
      font-family: 'DM Sans', sans-serif;
      overflow: hidden;
    }

    /* ── BACKGROUND ── */
    .bg {
      position: fixed;
      inset: 0;
      background-image: url("{{ asset('image/farmerlaptop.jpg') }}");
      background-size: cover;
      background-position: center 30%;
      z-index: 0;
    }
    .bg::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(
        115deg,
        rgba(10, 30, 12, 0.82) 0%,
        rgba(10, 30, 12, 0.55) 45%,
        rgba(10, 30, 12, 0.30) 100%
      );
    }

    /* ── LAYOUT ── */
    .page {
      position: relative;
      z-index: 1;
      display: flex;
      height: 100vh;
      align-items: center;
    }

    /* ── LEFT BRAND PANEL ── */
    .brand-panel {
      flex: 1;
      padding: 0 56px;
      color: var(--white);
      animation: fadeSlideLeft 0.9s ease both;
    }
    .brand-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: 0.2em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 20px;
    }
    .brand-eyebrow::before {
      content: '';
      display: block;
      width: 28px;
      height: 1px;
      background: var(--gold);
    }
    .brand-title {
      font-family: 'Playfair Display', serif;
      font-size: clamp(2.4rem, 4vw, 3.6rem);
      line-height: 1.12;
      font-weight: 700;
      color: var(--white);
      margin-bottom: 20px;
    }
    .brand-title span { color: var(--green-accent); }
    .brand-desc {
      font-size: 15px;
      font-weight: 300;
      color: rgba(255,255,255,0.65);
      line-height: 1.7;
      max-width: 380px;
      margin-bottom: 40px;
    }
    .brand-stats {
      display: flex;
      gap: 36px;
    }
    .stat-item { display: flex; flex-direction: column; gap: 4px; }
    .stat-num {
      font-family: 'Playfair Display', serif;
      font-size: 1.9rem;
      color: var(--green-accent);
      font-weight: 600;
      line-height: 1;
    }
    .stat-label {
      font-size: 11px;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.45);
    }

    /* ── GLASS CARD ── */
    .auth-card {
      width: 420px;
      min-height: 520px;
      margin-right: 72px;
      background: var(--glass-bg);
      border: 1px solid var(--glass-border);
      border-radius: var(--radius);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      padding: 44px 40px 40px;
      box-shadow: var(--shadow);
      animation: fadeSlideUp 0.9s 0.2s ease both;
      flex-shrink: 0;
    }

    /* ── TABS ── */
    .tab-bar {
      display: flex;
      background: rgba(255,255,255,0.06);
      border-radius: 10px;
      padding: 4px;
      margin-bottom: 32px;
    }
    .tab-btn {
      flex: 1;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: transparent;
      color: rgba(255,255,255,0.5);
      font-family: 'DM Sans', sans-serif;
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.25s;
    }
    .tab-btn.active {
      background: var(--green-mid);
      color: var(--white);
      box-shadow: 0 4px 12px rgba(45,106,53,0.45);
    }

    /* ── FORM PANELS ── */
    .form-panel { display: none; }
    .form-panel.active { display: block; }

    .form-label {
      display: block;
      font-size: 11.5px;
      font-weight: 500;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.5);
      margin-bottom: 7px;
    }
    .form-group { margin-bottom: 18px; }
    .form-control-custom {
      width: 100%;
      padding: 12px 16px;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      border-radius: 10px;
      color: var(--white);
      font-family: 'DM Sans', sans-serif;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    }
    .form-control-custom::placeholder { color: rgba(255,255,255,0.25); }
    .form-control-custom:focus {
      border-color: var(--green-accent);
      background: rgba(255,255,255,0.12);
      box-shadow: 0 0 0 3px rgba(76, 175, 96, 0.18);
    }
    .form-control-custom option { background: #1a3a1f; color: #fff; }

    /* ── SUBMIT BUTTON ── */
    .btn-submit {
      width: 100%;
      padding: 14px;
      margin-top: 8px;
      background: linear-gradient(135deg, var(--green-mid), var(--green-accent));
      border: none;
      border-radius: 10px;
      color: var(--white);
      font-family: 'DM Sans', sans-serif;
      font-size: 14.5px;
      font-weight: 500;
      letter-spacing: 0.04em;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
      box-shadow: 0 6px 20px rgba(45,106,53,0.4);
    }
    .btn-submit:hover {
      opacity: 0.92;
      transform: translateY(-1px);
      box-shadow: 0 10px 28px rgba(45,106,53,0.5);
    }
    .btn-submit:active { transform: translateY(0); }

    /* ── DIVIDER ── */
    .divider {
      display: flex;
      align-items: center;
      gap: 12px;
      margin: 20px 0;
      color: rgba(255,255,255,0.25);
      font-size: 12px;
    }
    .divider::before, .divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: rgba(255,255,255,0.12);
    }

    /* ── CARD HEADER ── */
    .card-header-text {
      margin-bottom: 28px;
    }
    .card-header-text h2 {
      font-family: 'Playfair Display', serif;
      font-size: 1.55rem;
      color: var(--white);
      font-weight: 600;
      margin-bottom: 4px;
    }
    .card-header-text p {
      font-size: 13px;
      color: rgba(255,255,255,0.45);
    }

    /* ── REMEMBER / FORGOT ── */
    .form-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
    }
    .remember {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12.5px;
      color: rgba(255,255,255,0.5);
      cursor: pointer;
    }
    .remember input[type="checkbox"] { accent-color: var(--green-accent); }
    .forgot-link {
      font-size: 12.5px;
      color: var(--green-accent);
      text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover { color: var(--gold); }

    /* ── LOGO MARK ── */
    .logo-mark {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 32px;
    }
    .logo-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--green-mid), var(--green-accent));
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .logo-text {
      font-family: 'Playfair Display', serif;
      font-size: 1.1rem;
      color: var(--white);
      font-weight: 600;
    }
    .logo-text span {
      display: block;
      font-family: 'DM Sans', sans-serif;
      font-size: 10px;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: rgba(255,255,255,0.4);
      font-weight: 400;
    }

    /* ── ANIMATIONS ── */
    @keyframes fadeSlideLeft {
      from { opacity: 0; transform: translateX(-30px); }
      to   { opacity: 1; transform: translateX(0); }
    }
    @keyframes fadeSlideUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 768px) {
      .page { flex-direction: column; justify-content: center; overflow-y: auto; padding: 24px 16px; }
      .brand-panel { display: none; }
      .auth-card { width: 100%; margin-right: 0; min-height: auto; }
      html, body { overflow: auto; }
    }

    /* ── ERROR MESSAGES (Laravel) ── */
    .alert-error {
      background: rgba(220, 53, 69, 0.15);
      border: 1px solid rgba(220, 53, 69, 0.35);
      border-radius: 8px;
      padding: 10px 14px;
      margin-bottom: 16px;
      font-size: 13px;
      color: #ff8080;
    }
    .alert-error ul { padding-left: 16px; margin: 0; }
  </style>
</head>
<body>

  <!-- Background image -->
  <div class="bg"></div>

  <div class="page">

    <!-- ── LEFT: BRAND ── -->
    <div class="brand-panel">
      <div class="brand-eyebrow">Farm Supply Management System</div>
      <h1 class="brand-title">
        Smarter Farming<br>

      </h1>
      <p class="brand-desc">
        Helps centralizes your inventory, livestock, and supplier records, to aid your farming needs.

    </div>

    <!-- ── RIGHT: AUTH CARD ── -->
    <div class="auth-card">



      <!-- Tabs -->
      <div class="tab-bar">
        <button class="tab-btn active" onclick="switchTab('login', this)">Sign In</button>
        <button class="tab-btn"        onclick="switchTab('register', this)">Register</button>
      </div>

      {{-- ── LOGIN PANEL ── --}}
      <div id="panel-login" class="form-panel active">
        <div class="card-header-text">
          <h2>Welcome back</h2>
          <p>Enter your credentials to continue</p>
        </div>

        {{-- Laravel validation errors --}}
        @if ($errors->any() && old('_form') === 'login')
          <div class="alert-error">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <input type="hidden" name="_form" value="login">

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control-custom"
              placeholder="you@example.com" value="{{ old('email') }}" required>
          </div>

          <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control-custom"
              placeholder="••••••••" required>
          </div>

          <div class="form-footer">
            <label class="remember">
              <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
          </div>

          <button type="submit" class="btn-submit">Sign In →</button>
        </form>
      </div>

      {{-- ── REGISTER PANEL ── --}}
      <div id="panel-register" class="form-panel">
        <div class="card-header-text">
          <h2>Create account</h2>
        </div>

        @if ($errors->any() && old('_form') === 'register')
          <div class="alert-error">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
          @csrf
          <input type="hidden" name="_form" value="register">

          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control-custom"
              placeholder="Juan dela Cruz" value="{{ old('name') }}" required>
          </div>

          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control-custom"
              placeholder="you@example.com" value="{{ old('email') }}" required>
          </div>




          <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control-custom"
              placeholder="Min. 8 characters" required>
          </div>

          <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control-custom"
              placeholder="Repeat password" required>
          </div>

          <select name="role" class="form-control-custom" required>
    <option value="" disabled selected>Select your role</option>
    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Farm Owner (Admin)</option>
    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Farm Staff</option>
</select>

          <button type="submit" class="btn-submit">Create Account →</button>
        </form>
      </div>

    </div>
    {{-- end auth-card --}}

  </div>

  <script>
    function switchTab(tab, btn) {
      // Update buttons
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Update panels
      document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
      document.getElementById('panel-' + tab).classList.add('active');
    }

    // If validation failed, re-open the correct tab
    const lastForm = "{{ old('_form') }}";
    if (lastForm === 'register') {
      document.querySelectorAll('.tab-btn')[1].click();
    }

    // If there are errors and no _form hint, default stays on login
  </script>

</body>
</html>