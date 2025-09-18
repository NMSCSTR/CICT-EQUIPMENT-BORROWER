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
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Poppins', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol']
          },
          colors: {
            'bg': '#0b1020',
            'card': '#111936',
            'card-2': '#0f1730',
            'text': '#e7ecf3',
            'muted': '#a8b3c7',
            'primary': '#4f8cff',
            'primary-600': '#3e74e0'
          }
        }
      }
    }
  </script>
  <style>
    /* DataTables custom styling for dark theme */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input{
      @apply bg-slate-700 text-slate-200 border-slate-600 rounded-lg px-3 py-2 placeholder-slate-400;
    }
    .dataTables_wrapper .dataTables_length label,
    .dataTables_wrapper .dataTables_filter label{
      @apply text-slate-300 text-sm font-medium;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button{
      @apply bg-slate-700 text-slate-200 border-slate-600 rounded-lg px-3 py-1 mx-1 hover:bg-slate-600 transition-colors;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
      @apply bg-gradient-to-b from-blue-500 to-blue-600 text-white border-blue-500;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
      @apply bg-slate-600 text-slate-100;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled{
      @apply bg-slate-800 text-slate-500 border-slate-700 cursor-not-allowed;
    }
    .dataTables_wrapper .dataTables_info{
      @apply text-slate-300 text-sm;
    }
    .dt-head{
      @apply flex justify-between items-center gap-4 mb-4 flex-wrap bg-slate-800 p-4 rounded-lg border border-slate-600;
    }
    .dt-head .dataTables_length,
    .dt-head .dataTables_filter{
      @apply m-0;
    }
    .dt-head .dataTables_length{
      @apply flex items-center gap-2;
    }
    .dt-head .dataTables_filter{
      @apply flex items-center gap-2;
    }
    .dt-head .dataTables_filter input{
      @apply w-64 max-w-[60vw];
    }
    .dt-foot{
      @apply flex justify-between items-center gap-4 mt-4 bg-slate-800 p-4 rounded-lg border border-slate-600;
    }
    .dt-foot .dataTables_info{
      @apply text-slate-300 text-sm;
    }
    .dt-foot .dataTables_paginate{
      @apply flex gap-1;
    }
    @media (max-width: 640px){
      .dt-head{
        @apply flex-col items-stretch gap-3;
      }
      .dt-head .dataTables_filter input{
        @apply w-full;
      }
      .dt-foot{
        @apply flex-col items-stretch gap-3;
      }
      .dt-foot .dataTables_paginate{
        @apply justify-center;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white font-sans">
  <div class="grid gap-4">
    <div class="bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 rounded-xl p-4 shadow-2xl">
      <h1 class="text-xl font-bold mb-1">Equipment Management</h1>
      <p class="text-slate-400 mb-3 text-sm">Manage equipment inventory based on the `equipment` table.</p>
      <div class="flex gap-3">
        <button class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-blue-500 bg-gradient-to-b from-blue-500 to-blue-600 text-white font-semibold hover:brightness-110 transition-all" id="openAdd">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
          Add Equipment
        </button>
        <a class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-600 bg-slate-800 text-slate-300 hover:bg-slate-700 transition-all" href="dashboard.php">Back to Dashboard</a>
      </div>
    </div>

    <div class="bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 rounded-xl p-6 shadow-2xl">
      <div class="bg-slate-800 rounded-lg overflow-hidden shadow-inner border border-slate-600">
        <div class="p-2 max-h-[60vh] overflow-auto">
          <table id="equipmentTable" class="w-full border-collapse">
            <thead>
              <tr>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">ID</th>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">Name</th>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">Description</th>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">Total</th>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">Available</th>
                <th class="sticky top-0 z-10 bg-slate-700 text-slate-200 border-b border-slate-600 px-3 py-3 text-left text-xs font-medium">Condition</th>
              </tr>
            </thead>
            <tbody class="bg-slate-800">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Add Equipment Modal -->
  <div class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-55 z-50" id="addModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="addTitle">
    <div class="w-full max-w-2xl bg-gradient-to-b from-slate-800 to-slate-900 border border-slate-700 rounded-xl p-6 text-white">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold" id="addTitle">Add Equipment</h2>
        <button class="bg-transparent border-0 text-slate-400 cursor-pointer p-2 rounded-lg hover:bg-slate-700 transition-colors" id="closeAdd" aria-label="Close">✕</button>
      </div>
      <form id="addForm" action="controller/equipment_create.php" method="post" class="space-y-4">
        <div class="grid gap-2">
          <label for="equipment_name" class="text-xs font-medium text-slate-300">Equipment Name</label>
          <input type="text" id="equipment_name" name="equipment_name" required class="w-full px-3 py-2 rounded-lg border border-slate-600 bg-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
        </div>
        <div class="grid gap-2">
          <label for="description" class="text-xs font-medium text-slate-300">Description</label>
          <textarea id="description" name="description" rows="3" placeholder="Short description (optional)" class="w-full px-3 py-2 rounded-lg border border-slate-600 bg-slate-700 text-white placeholder-slate-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"></textarea>
          <span class="text-xs text-slate-400">Keep it brief for easier scanning.</span>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="grid gap-2">
            <label for="quantity_total" class="text-xs font-medium text-slate-300">Total Quantity</label>
            <input type="number" id="quantity_total" name="quantity_total" min="0" value="0" required class="w-full px-3 py-2 rounded-lg border border-slate-600 bg-slate-700 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
          </div>
          <div class="grid gap-2">
            <label for="quantity_available" class="text-xs font-medium text-slate-300">Available Quantity</label>
            <input type="number" id="quantity_available" name="quantity_available" min="0" value="0" required class="w-full px-3 py-2 rounded-lg border border-slate-600 bg-slate-700 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            <span class="text-xs text-slate-400">Cannot exceed total quantity.</span>
          </div>
        </div>
        <div class="grid gap-2">
          <label for="equipment_condition" class="text-xs font-medium text-slate-300">Condition</label>
          <select id="equipment_condition" name="equipment_condition" class="w-full px-3 py-2 rounded-lg border border-slate-600 bg-slate-700 text-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            <option value="Good">Good</option>
            <option value="Fair">Damaged</option>
          </select>
        </div>
        <div class="flex gap-3 justify-end mt-4">
          <button type="button" class="px-4 py-2 rounded-lg border border-slate-600 bg-slate-800 text-slate-300 hover:bg-slate-700 transition-colors" id="cancelAdd">Cancel</button>
          <button type="submit" class="px-4 py-2 rounded-lg border border-blue-500 bg-gradient-to-b from-blue-500 to-blue-600 text-white font-semibold hover:brightness-110 transition-all">Save</button>
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
            { data: 'equipment_id', className: 'text-right' },
            { data: 'equipment_name', className: 'font-medium' },
            { data: 'description', render: function(d){ if(!d) return ''; return d.length>80 ? d.slice(0,80)+'…' : d; }, className: 'max-w-[520px] truncate' },
            { data: 'quantity_total', className: 'text-right font-medium' },
            { data: 'quantity_available', className: 'text-right font-medium' },
            { data: 'equipment_condition', render: function(d){
                if(!d) return '';
                const v = String(d).toLowerCase();
                if (v === 'good') return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">Good</span>';
                if (v === 'fair') return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">Fair</span>';
                return '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">' + d + '</span>';
              }
            }
          ],
          paging: true,
          searching: true,
          info: true,
          order: [[1,'asc']],
          dom: '<"dt-head"fl>rt<"dt-foot"ip>',
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          pageLength: 10,
          autoWidth: false,
          scrollX: true,
          stripeClasses: ['bg-slate-700',''],
          columnDefs: [
            { width: 70, targets: 0 },
            { width: 220, targets: 1 },
            { width: 120, targets: 3 },
            { width: 120, targets: 4 }
          ],
          language: {
            searchPlaceholder: 'Search equipment…'
          },
          createdRow: function(row, data, dataIndex) {
            $(row).addClass('hover:bg-slate-700 transition-colors');
            $(row).find('td').addClass('px-3 py-3 text-sm text-slate-200 border-b border-slate-600');
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
          addModal.classList.remove('hidden');
          addModal.classList.add('flex');
          setTimeout(()=>{ try { firstField && firstField.focus(); } catch(_) {} }, 0);
          document.body.style.overflow = 'hidden';
        } else {
          addModal.classList.add('hidden');
          addModal.classList.remove('flex');
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