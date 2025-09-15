<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php?error=' . urlencode('Please sign in to continue.'));
	exit;
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
$userType = isset($_SESSION['user_type']) ? ucfirst($_SESSION['user_type']) : 'User';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard • CICT</title>
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

    .container { min-height: 100dvh; display: grid; grid-template-rows: auto 1fr; }

    .nav {
      display: flex; align-items: center; justify-content: space-between;
      padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,.06);
      background: linear-gradient(180deg, rgba(17,25,54,.9), rgba(15,23,48,.9));
      position: sticky; top: 0; z-index: 10;
    }
    .brand { display: flex; align-items: center; gap: 10px; }
    .brand img { width: 28px; height: 28px; border-radius: 6px; }
    .brand h1 { font-size: 16px; margin: 0; letter-spacing: .2px; }
    .brand span { color: var(--muted); font-size: 12px; }

    .nav-actions { display: flex; align-items: center; gap: 10px; }
    .btn {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 8px 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,.08);
      color: #e7ecf3; background: rgba(8, 14, 32, .6); cursor: pointer;
    }
    .btn:hover { background: rgba(8, 14, 32, .8); }

    .content { padding: 22px; display: grid; gap: 16px; }

    .hero {
      background: linear-gradient(180deg, var(--card), var(--card-2));
      border: 1px solid rgba(255,255,255,.06);
      border-radius: var(--radius);
      padding: 22px;
      box-shadow: 0 10px 40px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
      display: grid; grid-template-columns: auto 1fr; gap: 12px; align-items: center;
    }
    .hero h2 { margin: 0 0 6px 0; font-size: 20px; }
    .hero p { margin: 0; color: var(--muted); font-size: 14px; }

    .grid { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; }
    .card {
      background: linear-gradient(180deg, var(--card), var(--card-2));
      border: 1px solid rgba(255,255,255,.06);
      border-radius: var(--radius);
      padding: 18px;
      box-shadow: 0 10px 40px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05);
    }

    .card h3 { margin: 0 0 10px 0; font-size: 16px; display: inline-flex; align-items: center; gap: 8px; }
    .card p { margin: 0; color: var(--muted); font-size: 13px; }

    .quick { grid-column: span 12; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; }
    .quick a {
      display: flex; align-items: center; gap: 10px;
      text-decoration: none; color: var(--text);
      border: 1px solid rgba(255,255,255,.08); border-radius: 12px;
      padding: 12px; background: rgba(8,14,32,.5);
      transition: transform .06s ease, background .15s ease;
    }
    .quick a:hover { background: rgba(8,14,32,.8); transform: translateY(-1px); }

    .icon { width: 18px; height: 18px; }
    .hero-icon { width: 28px; height: 28px; color: #c9d7ff; }

    @media (max-width: 640px) { .content { padding: 18px; } }
    @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
  </style>
</head>
<body>
  <div class="container">
    <nav class="nav" role="navigation" aria-label="Top Navigation">
      <div class="brand">
        <img src="cictlogo.png" alt="CICT logo" onerror="this.style.display='none'">
        <div>
          <h1>CICT Borrower</h1>
          <span><?php echo htmlspecialchars($userType); ?></span>
        </div>
      </div>
      <div class="nav-actions">
        <span style="color:#cbd6f7; font-size:13px;">Hi, <?php echo htmlspecialchars($name); ?></span>
        <form action="controller/logout.php" method="post" style="margin:0;">
          <button class="btn" type="submit">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>
            Logout
          </button>
        </form>
      </div>
    </nav>

    <main class="content" role="main">
      <section class="hero">
        <svg class="hero-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 17l-5 3 1.9-5.7L4 10h6l2-6 2 6h6l-4.9 4.3L17 20z"/></svg>
        <div>
          <h2>Welcome back, <?php echo htmlspecialchars($name); ?>!</h2>
          <p>Use the quick actions below to get started.</p>
        </div>
      </section>

      <section class="grid">
        <div class="card" style="grid-column: span 12;">
          <h3>
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="14" rx="2"/><path d="M3 7h18"/><path d="M7 17h10"/></svg>
            Overview
          </h3>
          <p>Your recent activity and status will appear here.</p>
        </div>
        <div class="quick">
          <a href="users.php" aria-label="Manage users">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Manage Users
          </a>
          <a href="equipment.php" aria-label="Equipment management">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M7 7V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/></svg>
            Equipment Management
          </a>
          <a href="borrow_transaction.php" aria-label="Borrow transactions">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="14" rx="2"/><path d="M3 7h18"/><path d="M8 13h8M8 10h4"/></svg>
            Borrow Transactions
          </a>
        </div>
      </section>
    </main>
  </div>

  <script>
    (function(){
      const params = new URLSearchParams(window.location.search);
      const ok = params.get('success');
      const err = params.get('error');
      function showSwal(){
        if (typeof Swal === 'undefined') return;
        if (ok) { Swal.fire({ icon: 'success', title: 'Welcome', text: ok, confirmButtonColor: '#4f8cff' }); }
        if (err) { Swal.fire({ icon: 'error', title: 'Notice', text: err, confirmButtonColor: '#4f8cff' }); }
      }
      if (!document.getElementById('swal2-script')) {
        const s = document.createElement('script');
        s.id = 'swal2-script';
        s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js';
        s.onload = showSwal;
        document.head.appendChild(s);
      } else { showSwal(); }
    })();
  </script>
</body>
</html>
