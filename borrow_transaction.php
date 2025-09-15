<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php?error=' . urlencode('Please sign in to continue.'));
	exit;
}
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'User';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Borrow Transactions • CICT</title>
  <style>
    :root { --bg:#0b1020; --card:#111936; --card-2:#0f1730; --text:#e7ecf3; --muted:#a8b3c7; --primary:#4f8cff; --primary-600:#3e74e0; --focus:0 0 0 3px rgba(79,140,255,.35); --radius:14px; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial; background:radial-gradient(1200px 800px at 80% -10%, rgba(79,140,255,.25), transparent 60%), radial-gradient(1000px 700px at -10% 110%, rgba(79,140,255,.18), transparent 60%), var(--bg); color:var(--text)}
    .wrap{min-height:100dvh; padding:22px; display:grid; gap:16px}
    .card{background:linear-gradient(180deg,var(--card),var(--card-2)); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); padding:18px; box-shadow:0 10px 40px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05)}
    h1{margin:0 0 6px 0; font-size:20px}
    p{margin:0; color:var(--muted)}
    a.btn{display:inline-block; margin-top:12px; padding:10px 12px; border-radius:10px; border:1px solid rgba(255,255,255,.08); color:#e7ecf3; text-decoration:none; background:rgba(8,14,32,.6)}
    a.btn:hover{background:rgba(8,14,32,.8)}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Borrow Transactions</h1>
      <p>Track and manage borrow requests from the `borrow_transaction` table.</p>
      <a class="btn" href="dashboard.php">Back to Dashboard</a>
    </div>
  </div>
  <script>
    (function(){
      const params = new URLSearchParams(window.location.search);
      const ok = params.get('success'); const err = params.get('error');
      function showSwal(){ if(typeof Swal==='undefined')return; if(ok){Swal.fire({icon:'success',title:'Success',text:ok,confirmButtonColor:'#4f8cff'});} if(err){Swal.fire({icon:'error',title:'Notice',text:err,confirmButtonColor:'#4f8cff'});} }
      if(!document.getElementById('swal2-script')){const s=document.createElement('script'); s.id='swal2-script'; s.src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'; s.onload=showSwal; document.head.appendChild(s);} else { showSwal(); }
    })();
  </script>
</body>
</html> 