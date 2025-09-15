<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register • CICT</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #0b1020;
      --card: #111936;
      --card-2: #0f1730;
      --text: #e7ecf3;
      --muted: #a8b3c7;
      --primary: #4f8cff;
      --primary-600: #3e74e0;
      --danger: #ff6961;
      --focus: 0 0 0 3px rgba(79, 140, 255, .35);
      --radius: 14px;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body {
      margin: 0;
      font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
      background: radial-gradient(1200px 800px at 80% -10%, rgba(79,140,255,.25), transparent 60%),
                  radial-gradient(1000px 700px at -10% 110%, rgba(79,140,255,.18), transparent 60%),
                  var(--bg);
      color: var(--text);
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }

    .container {
      min-height: 100dvh;
      display: grid;
      place-items: center;
      padding: 24px;
    }

    .card {
      width: 100%;
      max-width: 460px;
      background: linear-gradient(180deg, var(--card), var(--card-2));
      border: 1px solid rgba(255,255,255,.06);
      border-radius: var(--radius);
      padding: 28px;
      box-shadow: 0 20px 60px rgba(0,0,0,.4), inset 0 1px 0 rgba(255,255,255,.05);
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
    }
    .brand img { width: 40px; height: 40px; border-radius: 8px; }
    .brand h1 { font-size: 22px; margin: 0; letter-spacing: .2px; }
    .brand span { color: var(--muted); font-weight: 500; font-size: 13px; }

    .subtitle { color: var(--muted); margin: 0 0 22px 0; font-size: 14px; }

    form { display: grid; gap: 14px; }

    .field { display: grid; gap: 8px; }
    .label-row { display: flex; justify-content: space-between; align-items: baseline; }
    label { font-weight: 600; font-size: 13px; color: #dbe6ff; }
    .hint { color: var(--muted); font-size: 12px; }

    .input-wrap { position: relative; }
    .input {
      width: 100%;
      padding: 12px 44px 12px 40px;
      border-radius: 12px;
      border: 1px solid rgba(255,255,255,.08);
      background: rgba(8, 14, 32, .7);
      color: var(--text);
      outline: none;
      font-size: 14px;
      transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .input::placeholder { color: #8fa0bf; }
    .input:focus { border-color: rgba(79,140,255,.65); box-shadow: var(--focus); background: rgba(8,14,32,.9); }

    .left-icon, .right-adorn {
      position: absolute;
      top: 0; bottom: 0;
      display: grid; place-items: center;
      width: 38px;
      color: #b7c4dd;
      pointer-events: none;
    }
    .left-icon { left: 2px; }

    .right-adorn {
      right: 4px;
      width: auto;
      gap: 6px;
      display: flex;
      align-items: center;
      pointer-events: auto;
    }

    .icon { width: 18px; height: 18px; display: inline-block; }

    .ghost-btn {
      border: 0;
      background: transparent;
      color: #b7c4dd;
      padding: 6px;
      border-radius: 8px;
      cursor: pointer;
      transition: background .15s ease, color .15s ease, transform .08s ease;
    }
    .ghost-btn:hover { background: rgba(255,255,255,.06); color: #eaf0ff; }
    .ghost-btn:active { transform: translateY(1px); }
    .ghost-btn:focus-visible { outline: none; box-shadow: var(--focus); }

    .caps { margin-top: 6px; display: none; align-items: center; gap: 8px; font-size: 12px; color: #ffb74d; }
    .caps[aria-hidden="false"] { display: inline-flex; }

    .submit { width: 100%; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(79,140,255,.6); background: linear-gradient(180deg, var(--primary), var(--primary-600)); color: white; font-weight: 700; letter-spacing: .2px; cursor: pointer; transition: filter .15s ease, transform .06s ease, box-shadow .2s ease; }
    .submit:hover { filter: brightness(1.02); }
    .submit:active { transform: translateY(1px); }
    .submit:disabled { filter: grayscale(.4) brightness(.9); cursor: not-allowed; opacity: .85; }

    .spinner { width: 16px; height: 16px; margin-right: 8px; vertical-align: -3px; display: none; }
    .submit[data-loading="true"] .spinner { display: inline-block; }

    .visually-hidden { position: absolute !important; height: 1px; width: 1px; overflow: hidden; clip: rect(1px, 1px, 1px, 1px); white-space: nowrap; border: 0; padding: 0; margin: -1px; }

    .alt { margin-top: 10px; color: var(--muted); font-size: 13px; text-align: center; }
    .alt a { color: #dbe6ff; text-decoration: none; }
    .alt a:hover { text-decoration: underline; }

    @media (max-width: 480px) { .card { padding: 22px; border-radius: 12px; } .brand h1 { font-size: 20px; } }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
  </style>
</head>
<body>
  <div class="container">
    <main class="card" role="main">
      <div class="brand" aria-label="Site">
        <img src="cictlogo.png" alt="CICT logo" onerror="this.style.display='none'">
                <div>
          <h1>Create your account</h1>
          <span>Join CICT to get started</span>
        </div>
      </div>

      <p class="subtitle">Fill in your details to create a new account.</p>

      <form id="registerForm" action="controller/registration.php" method="post" novalidate aria-describedby="formStatus" aria-live="polite">
        <div class="field">
          <div class="label-row">
            <label for="user_type">User Type</label>
            <span class="hint" id="typeHint">Select your role</span>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <select class="input" id="user_type" name="user_type" aria-describedby="typeHint" required>
              <option value="" disabled selected>Select type</option>
              <option value="student">Student</option>
              <option value="instructor">Instructor</option>
              <option value="staff">Staff</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>

        <div class="field">
          <div class="label-row">
            <label for="name">Full Name</label>
            <span class="hint" id="nameHint">Your first and last name</span>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            <input class="input" type="text" id="name" name="name" placeholder="Jane Doe" autocomplete="name" aria-describedby="nameHint" required>
          </div>
        </div>

        <div class="field">
          <div class="label-row">
            <label for="email">Email</label>
            <span class="hint" id="emailHint">We’ll send a confirmation</span>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
            </span>
            <input class="input" type="email" id="email" name="email" placeholder="jane@example.com" autocomplete="email" aria-describedby="emailHint" required>
          </div>
        </div>

        <div class="field">
          <div class="label-row">
            <label for="contact_number">Contact Number</label>
            <span class="hint" id="contactHint">Optional</span>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="3" width="20" height="18" rx="3"/><path d="M7 7h10M7 12h6M7 17h8"/></svg>
            </span>
            <input class="input" type="tel" id="contact_number" name="contact_number" placeholder="e.g. 0917 123 4567" autocomplete="tel" aria-describedby="contactHint">
          </div>
        </div>

        <div class="field">
          <div class="label-row">
            <label for="student_id">Student ID</label>
            <span class="hint" id="sidHint">Optional for students</span>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M7 9h10M7 13h6"/></svg>
            </span>
            <input class="input" type="text" id="student_id" name="student_id" placeholder="e.g. 2025-12345" aria-describedby="sidHint" autocomplete="off">
          </div>
        </div>

        <div class="field">
          <div class="label-row">
            <label for="password">Password</label>
          </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input class="input" type="password" id="password" name="password" autocomplete="new-password" placeholder="Create a password" aria-describedby="passwordCaps passwordHelp" minlength="6" required>
            <span class="right-adorn">
              <button type="button" class="ghost-btn" id="togglePassword" aria-label="Show password" aria-controls="password" aria-pressed="false" title="Show password">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </span>
          </div>
          <span id="passwordCaps" class="caps" aria-hidden="true" role="status" aria-live="polite">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l9 9h-6v11H9V11H3l9-9z"/></svg>
            Caps Lock is on
          </span>
          <span id="passwordHelp" class="visually-hidden">Use the toggle button to show or hide password.</span>
                </div>

        <div class="field">
          <div class="label-row">
            <label for="confirm">Confirm Password</label>
                </div>
          <div class="input-wrap">
            <span class="left-icon" aria-hidden="true">
              <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input class="input" type="password" id="confirm" name="confirm_password" autocomplete="new-password" placeholder="Re-enter your password" aria-describedby="confirmCaps confirmHelp" minlength="6" required>
            <span class="right-adorn">
              <button type="button" class="ghost-btn" id="toggleConfirm" aria-label="Show confirm password" aria-controls="confirm" aria-pressed="false" title="Show confirm password">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </span>
                </div>
          <span id="confirmCaps" class="caps" aria-hidden="true" role="status" aria-live="polite">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l9 9h-6v11H9V11H3l9-9z"/></svg>
            Caps Lock is on
          </span>
          <span id="confirmHelp" class="visually-hidden">Use the toggle button to show or hide confirm password.</span>
        </div>

        <label style="display:flex; align-items:center; gap:8px; font-size:13px; color:#cbd6f7;">
          <input type="checkbox" id="terms" required style="width:16px; height:16px; accent-color: var(--primary);">
          I agree to the <a href="#" class="a-muted" style="margin-left:4px;">Terms & Privacy</a>
        </label>

        <button id="submitBtn" class="submit" type="submit">
          <svg class="spinner" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="9" stroke="rgba(255,255,255,.4)" stroke-width="3"/>
            <path d="M21 12a9 9 0 0 0-9-9" stroke="white" stroke-width="3">
              <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="0.8s" repeatCount="indefinite"/>
            </path>
          </svg>
          <span class="btn-text">Create account</span>
        </button>

        <div id="formStatus" class="visually-hidden" role="status" aria-live="polite"></div>

        <p class="alt">Already have an account? <a href="login.php">Sign in</a></p>
      </form>
</main>
  </div>

  <script>
    (function(){
      const form = document.getElementById('registerForm');
      const passwordInput = document.getElementById('password');
      const confirmInput = document.getElementById('confirm');
      const caps1 = document.getElementById('passwordCaps');
      const caps2 = document.getElementById('confirmCaps');
      const togglePassword = document.getElementById('togglePassword');
      const toggleConfirm = document.getElementById('toggleConfirm');
      const submitBtn = document.getElementById('submitBtn');
      const btnText = submitBtn.querySelector('.btn-text');

      function setToggle(btn, input, labelShow, labelHide) {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-pressed', String(show));
        btn.setAttribute('aria-label', show ? labelHide : labelShow);
        btn.title = show ? labelHide : labelShow;
        btn.innerHTML = show
          ? '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.8 21.8 0 0 1 5.06-6.94m4-2.23C10.69 2.29 11.34 2.25 12 2.25c7 0 11 8 11 8a21.8 21.8 0 0 1-3.52 4.88M1 1l22 22"/></svg>'
          : '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>';
        input.focus({ preventScroll: true });
      }

      togglePassword.addEventListener('click', function(){ setToggle(togglePassword, passwordInput, 'Show password', 'Hide password'); });
      toggleConfirm.addEventListener('click', function(){ setToggle(toggleConfirm, confirmInput, 'Show confirm password', 'Hide confirm password'); });

      function updateCaps(elm, container) {
        return function(e){
          const caps = e.getModifierState && e.getModifierState('CapsLock');
          container.setAttribute('aria-hidden', caps ? 'false' : 'true');
        }
      }
      passwordInput.addEventListener('keydown', updateCaps(passwordInput, caps1));
      passwordInput.addEventListener('keyup', updateCaps(passwordInput, caps1));
      passwordInput.addEventListener('focus', function(e){ updateCaps(passwordInput, caps1)(e); });
      passwordInput.addEventListener('blur', function(){ caps1.setAttribute('aria-hidden', 'true'); });

      confirmInput.addEventListener('keydown', updateCaps(confirmInput, caps2));
      confirmInput.addEventListener('keyup', updateCaps(confirmInput, caps2));
      confirmInput.addEventListener('focus', function(e){ updateCaps(confirmInput, caps2)(e); });
      confirmInput.addEventListener('blur', function(){ caps2.setAttribute('aria-hidden', 'true'); });

      function setLoading(isLoading) {
        if (isLoading) {
          submitBtn.dataset.loading = 'true';
          submitBtn.disabled = true;
          btnText.textContent = 'Creating…';
          form.setAttribute('aria-busy', 'true');
        } else {
          delete submitBtn.dataset.loading;
          submitBtn.disabled = false;
          btnText.textContent = 'Create account';
          form.removeAttribute('aria-busy');
        }
      }

      form.addEventListener('submit', function(e){
        // Basic client validation: matching passwords
        if (passwordInput.value !== confirmInput.value) {
          e.preventDefault();
          document.getElementById('formStatus').textContent = 'Passwords do not match.';
          confirmInput.focus();
          return;
        }
        if (!form.checkValidity()) { return; }
        setLoading(true);
        // If action not wired yet, simulate
        if (!form.action || form.action === '#') {
          e.preventDefault();
          setTimeout(function(){ setLoading(false); document.getElementById('formStatus').textContent = 'Demo submission complete.'; }, 900);
        }
      });

      window.addEventListener('pageshow', function(){ setLoading(false); });
    })();
  </script>
  <script>
    (function(){
      const params = new URLSearchParams(window.location.search);
      const err = params.get('error');
      const ok = params.get('success');
      function showSwal(){
        if (typeof Swal === 'undefined') return;
        if (err) {
          Swal.fire({ icon: 'error', title: 'Registration failed', text: err, confirmButtonColor: '#4f8cff' });
        } else if (ok) {
          Swal.fire({ icon: 'success', title: 'Success', text: ok, confirmButtonColor: '#4f8cff' });
        }
      }
      if (!document.getElementById('swal2-script')) {
        const s = document.createElement('script');
        s.id = 'swal2-script';
        s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
        s.onload = showSwal;
        document.head.appendChild(s);
      } else {
        showSwal();
      }
    })();
  </script>
</body>
</html>
