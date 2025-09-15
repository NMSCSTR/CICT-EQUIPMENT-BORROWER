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
  <title>Equipment • CICT</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <style>
    :root { --bg:#0b1020; --card:#111936; --card-2:#0f1730; --text:#e7ecf3; --muted:#a8b3c7; --primary:#4f8cff; --primary-600:#3e74e0; --focus:0 0 0 3px rgba(79,140,255,.35); --radius:14px; }
    *{box-sizing:border-box} html,body{height:100%}
    body{margin:0;font-family:'Poppins', ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background:radial-gradient(1200px 800px at 80% -10%, rgba(79,140,255,.25), transparent 60%), radial-gradient(1000px 700px at -10% 110%, rgba(79,140,255,.18), transparent 60%), var(--bg); color:var(--text)}
    .wrap{min-height:100dvh; padding:22px; display:grid; gap:16px}
    .card{background:linear-gradient(180deg,var(--card),var(--card-2)); border:1px solid rgba(255,255,255,.06); border-radius:var(--radius); padding:18px; box-shadow:0 10px 40px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.05)}
    h1{margin:0 0 6px 0; font-size:20px}
    p{margin:0; color:var(--muted)}
    .actions{display:flex; gap:10px; margin-top:10px}
    .btn{display:inline-flex; align-items:center; gap:8px; padding:10px 12px; border-radius:10px; border:1px solid rgba(255,255,255,.08); color:#e7ecf3; background:rgba(8,14,32,.6); cursor:pointer; text-decoration:none}
    .btn:hover{background:rgba(8,14,32,.8)}
    .btn.primary{ border:1px solid rgba(79,140,255,.6); background: linear-gradient(180deg, var(--primary), var(--primary-600)); color:#fff; font-weight:700 }
    .btn.primary:hover{ filter: brightness(1.02) }

    table{width:100%; border-collapse: collapse;}
    thead th{ text-align:left; font-size:12px; color:#cbd6f7; border-bottom:1px solid rgba(255,255,255,.12); padding:10px; position: sticky; top: 0; z-index: 1; background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02)); backdrop-filter: blur(2px); }
    tbody td{ font-size:13px; color:#e7ecf3; border-bottom:1px solid rgba(255,255,255,.06); padding:10px }
    tbody tr:hover{ background: rgba(255,255,255,.03); }
    .table-white{ background:#ffffff; border-radius:12px; overflow:hidden; box-shadow: 0 1px 0 rgba(0,0,0,.04) inset; }
    .table-white .table-inner{ padding: 8px; max-height: 60vh; overflow: auto; }
    .table-white table{ background:#fff; }
    .table-white table thead th{ position: sticky; top: 0; z-index: 2; background:#f5f7fb; color:#1c2434; border-bottom:1px solid #e6eaf2; padding:12px; box-shadow: 0 1px 0 #e6eaf2, 0 2px 6px rgba(0,0,0,.03); }
    .table-white table tbody td{ color:#1c2434; border-bottom:1px solid #eef2f8; font-size:14px; line-height:1.45; padding:12px }
    .table-white table tbody tr:hover{ background:#f3f7ff; }
    .table-white table tbody tr.dt-row-stripe{ background:#f7faff; }
    .table-white table td:nth-child(3){ max-width: 520px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .table-white .dataTables_wrapper .dataTables_length select,
    .table-white .dataTables_wrapper .dataTables_filter input{
      background:#fff; color:#1c2434; border:1px solid #e6eaf2;
    }
    .table-white .dataTables_wrapper .dataTables_paginate .paginate_button{
      background:#fff; color:#1c2434 !important; border:1px solid #e6eaf2;
    }
    .table-white .dataTables_wrapper .dataTables_paginate .paginate_button.current{
      background: linear-gradient(180deg, var(--primary), var(--primary-600)); color:#fff !important; border-color: rgba(79,140,255,.6);
    }
    .badge{ display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; line-height:1; font-weight:600 }
    .badge.good{ background: rgba(76, 175, 80, .18); color:#b7ffbf; border:1px solid rgba(76,175,80,.35); }
    .badge.fair{ background: rgba(255, 193, 7, .18); color:#ffe9a6; border:1px solid rgba(255,193,7,.35); }
    .badge.poor{ background: rgba(244, 67, 54, .18); color:#ffb3ad; border:1px solid rgba(244,67,54,.35); }

    /* DataTables theming */
    .dataTables_wrapper { color: var(--text); }
    .dt-head{ display:flex; justify-content: space-between; align-items:center; gap:12px; margin: 6px 0 10px; flex-wrap: wrap; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter{ margin:0 !important; }
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input{
      background: rgba(8,14,32,.7);
      color: var(--text);
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 10px;
      padding: 8px 10px;
    }
    .dataTables_wrapper .dataTables_length select{ height: 36px; }
    .dataTables_wrapper .dataTables_filter input{ height: 36px; width: 240px; max-width: 60vw; }
    .dataTables_wrapper .dataTables_filter input::placeholder{ color:#8fa0bf; }
    .dataTables_wrapper .dataTables_paginate .paginate_button{
      background: rgba(8,14,32,.6);
      color: var(--text) !important;
      border: 1px solid rgba(255,255,255,.12);
      border-radius: 10px;
      padding: 6px 10px;
      margin: 2px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
      background: linear-gradient(180deg, var(--primary), var(--primary-600));
      border-color: rgba(79,140,255,.6);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{ background: rgba(8,14,32,.85); }
    .dataTables_wrapper .dataTables_info{ color: var(--muted); }
    thead th { background: linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02)); }
    @media (max-width: 520px){
      .dt-head{ flex-direction: column; align-items: stretch; }
      .dataTables_wrapper .dataTables_filter input{ width: 100%; }
    }

    /* Modal */
    .modal{ position: fixed; inset: 0; display: none; align-items: center; justify-content: center; background: rgba(0,0,0,.55); z-index: 1000; }
    .modal[aria-hidden="false"]{ display: flex; }
    .modal-card{ width: 100%; max-width: 560px; background: linear-gradient(180deg,var(--card),var(--card-2)); border:1px solid rgba(255,255,255,.08); border-radius: 14px; padding: 18px; color: var(--text); }
    .modal-header{ display:flex; align-items:center; justify-content: space-between; margin-bottom: 12px; }
    .modal-title{ margin:0; font-size:18px }
    .close{ background:transparent; border:0; color:#cbd6f7; cursor:pointer; padding:6px; border-radius:8px }
    .close:hover{ background: rgba(255,255,255,.06) }

    .field{ display:grid; gap:6px; margin:10px 0 }
    .field-row{ display:grid; grid-template-columns: 1fr 1fr; gap:10px }
    label{ font-size:12px; color:#dbe6ff }
    input, textarea, select{ width:100%; padding:10px 12px; border-radius:10px; border:1px solid rgba(255,255,255,.1); background: rgba(8,14,32,.7); color: var(--text);} 
    .hint{ color:#a8b3c7; font-size:12px }

    .submit{ padding: 10px 12px; border-radius: 10px; border: 1px solid rgba(79,140,255,.6); background: linear-gradient(180deg, var(--primary), var(--primary-600)); color: white; font-weight: 700; cursor: pointer; }
  </style>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Equipment Management</h1>
      <p>Manage equipment inventory based on the `equipment` table.</p>
      <div class="actions">
        <button class="btn primary" id="openAdd">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
          Add Equipment
        </button>
        <a class="btn" href="dashboard.php">Back to Dashboard</a>
      </div>
    </div>

    <div class="card">
      <div class="table-white">
        <div class="table-inner">
        <table id="equipmentTable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Total</th>
            <th>Available</th>
            <th>Condition</th>
          </tr>
        </thead>
        <tbody></tbody>
        </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Equipment Modal -->
  <div class="modal" id="addModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="modal-card">
      <div class="modal-header">
        <h2 class="modal-title" id="addTitle">Add Equipment</h2>
        <button class="close" id="closeAdd" aria-label="Close">✕</button>
      </div>
      <form id="addForm" action="controller/equipment_create.php" method="post">
        <div class="field">
          <label for="equipment_name">Equipment Name</label>
          <input type="text" id="equipment_name" name="equipment_name" required>
        </div>
        <div class="field">
          <label for="description">Description</label>
          <textarea id="description" name="description" rows="3" placeholder="Short description (optional)"></textarea>
          <span class="hint">Keep it brief for easier scanning.</span>
        </div>
        <div class="field-row">
          <div class="field">
            <label for="quantity_total">Total Quantity</label>
            <input type="number" id="quantity_total" name="quantity_total" min="0" value="0" required>
          </div>
          <div class="field">
            <label for="quantity_available">Available Quantity</label>
            <input type="number" id="quantity_available" name="quantity_available" min="0" value="0" required>
            <span class="hint">Cannot exceed total quantity.</span>
          </div>
        </div>
        <div class="field">
          <label for="equipment_condition">Condition</label>
          <select id="equipment_condition" name="equipment_condition">
            <option value="Good">Good</option>
            <option value="Fair">Fair</option>
            <option value="Poor">Poor</option>
          </select>
        </div>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:10px">
          <button type="button" class="btn" id="cancelAdd">Cancel</button>
          <button type="submit" class="submit">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
  <script>
    (function(){
      // SweetAlert2 loader
      const params = new URLSearchParams(window.location.search);
      const ok = params.get('success'); const err = params.get('error');
      function showSwal(){ if(typeof Swal==='undefined')return; if(ok){Swal.fire({icon:'success',title:'Success',text:ok,confirmButtonColor:'#4f8cff'});} if(err){Swal.fire({icon:'error',title:'Notice',text:err,confirmButtonColor:'#4f8cff'});} }
      if(!document.getElementById('swal2-script')){const s=document.createElement('script'); s.id='swal2-script'; s.src='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js'; s.onload=showSwal; document.head.appendChild(s);} else { showSwal(); }

      // DataTable init (jQuery DataTables)
      $(function(){
        $('#equipmentTable').DataTable({
          ajax: { url: 'controller/equipment_list.php', dataSrc: 'data' },
          columns: [
            { data: 'equipment_id', className: 'dt-right' },
            { data: 'equipment_name' },
            { data: 'description', render: function(d){ if(!d) return ''; return d.length>80 ? d.slice(0,80)+'…' : d; } },
            { data: 'quantity_total', className: 'dt-right' },
            { data: 'quantity_available', className: 'dt-right' },
            { data: 'equipment_condition', render: function(d){
                if(!d) return '';
                const v = String(d).toLowerCase();
                if (v === 'good') return '<span class="badge good">Good</span>';
                if (v === 'fair') return '<span class="badge fair">Fair</span>';
                return '<span class="badge poor">' + d + '</span>';
              }
            }
          ],
          paging: true,
          searching: true,
          info: true,
          order: [[1,'asc']],
          dom: '<"dt-head"lf>rt<"dt-foot"ip>',
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          pageLength: 10,
          autoWidth: false,
          scrollX: true,
          stripeClasses: ['dt-row-stripe',''],
          columnDefs: [
            { width: 70, targets: 0 },
            { width: 220, targets: 1 },
            { width: 120, targets: 3 },
            { width: 120, targets: 4 }
          ],
          language: {
            searchPlaceholder: 'Search equipment…'
          }
        });
      });

      // Modal logic
      const addModal = document.getElementById('addModal');
      const openAdd = document.getElementById('openAdd');
      const closeAdd = document.getElementById('closeAdd');
      const cancelAdd = document.getElementById('cancelAdd');
      const firstField = document.getElementById('equipment_name');
      const totalField = document.getElementById('quantity_total');
      const availField = document.getElementById('quantity_available');

      function setModal(open){
        addModal.setAttribute('aria-hidden', open ? 'false' : 'true');
        if (open) {
          setTimeout(()=>{ try { firstField && firstField.focus(); } catch(_) {} }, 0);
          document.body.style.overflow = 'hidden';
        } else {
          document.body.style.overflow = '';
          openAdd && openAdd.focus();
        }
      }
      openAdd.addEventListener('click', ()=> setModal(true));
      closeAdd.addEventListener('click', ()=> setModal(false));
      cancelAdd.addEventListener('click', ()=> setModal(false));
      addModal.addEventListener('click', (e)=>{ if(e.target === addModal){ setModal(false); } });
      window.addEventListener('keydown', (e)=>{ if(addModal.getAttribute('aria-hidden') === 'false' && e.key === 'Escape'){ setModal(false); } });

      // Keep available <= total in the form
      function clampAvailability(){
        const t = Math.max(0, parseInt(totalField.value || '0', 10));
        let a = Math.max(0, parseInt(availField.value || '0', 10));
        if (a > t) { a = t; availField.value = String(a); }
        availField.max = String(t);
      }
      totalField.addEventListener('input', clampAvailability);
      availField.addEventListener('input', clampAvailability);

      // After successful create, the page will redirect with a success param; DataTable loads fresh on each page load
    })();
  </script>
</body>
</html> 