<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php?error=' . urlencode('Please sign in to continue.')); exit; }
$title = 'Instructor Dashboard';
require_once __DIR__ . '/config.php';

$user_id = (int)$_SESSION['user_id'];

// Fetch my transactions (as user)
$txSql = "SELECT bt.transaction_id, e.equipment_name, bt.borrow_date, bt.return_date, bt.quantity_borrowed, bt.status, bt.purpose
          FROM borrow_transaction bt JOIN equipment e ON e.equipment_id = bt.equipment_id
          WHERE bt.user_id = ? ORDER BY bt.borrow_date DESC LIMIT 100";
$stmt = mysqli_prepare($conn, $txSql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$tx = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Pending requests overview
$rq = mysqli_query($conn, "SELECT ir.request_id, u.name, e.equipment_name, ir.quantity_requested, ir.request_date, ir.status
                            FROM item_request ir JOIN users u ON u.user_id = ir.user_id JOIN equipment e ON e.equipment_id = ir.equipment_id
                            ORDER BY ir.request_date DESC LIMIT 50");

include __DIR__ . '/includes/instructor_header.php';
?>
<main class="flex-1">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Instructor Dashboard</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">My Borrow Transactions</h3>
        <div class="overflow-x-auto">
          <table id="myTx" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php while ($row = $tx->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo (int)$row['transaction_id']; ?></td>
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($row['equipment_name']); ?></td>
                <td class="px-3 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($row['borrow_date']); ?></td>
                <td class="px-3 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($row['return_date'] ?? ''); ?></td>
                <td class="px-3 py-2 text-sm text-gray-700"><?php echo (int)$row['quantity_borrowed']; ?></td>
                <td class="px-3 py-2 text-sm"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-700"><?php echo htmlspecialchars($row['status'] ?? ''); ?></span></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">Recent Item Requests</h3>
        <div class="overflow-x-auto">
          <table id="reqTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php while ($row = $rq->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo (int)$row['request_id']; ?></td>
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($row['name']); ?></td>
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo htmlspecialchars($row['equipment_name']); ?></td>
                <td class="px-3 py-2 text-sm text-gray-700"><?php echo (int)$row['quantity_requested']; ?></td>
                <td class="px-3 py-2 text-sm text-gray-700"><?php echo htmlspecialchars($row['request_date']); ?></td>
                <td class="px-3 py-2 text-sm"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-700"><?php echo htmlspecialchars($row['status'] ?? ''); ?></span></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
  $(function(){
    $('#myTx').DataTable({ responsive: true, pageLength: 5, order: [[2, 'desc']] });
    $('#reqTable').DataTable({ responsive: true, pageLength: 5, order: [[4, 'desc']] });
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

