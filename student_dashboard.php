<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: login.php?error=' . urlencode('Please sign in to continue.')); exit; }
$title = 'Student Dashboard';
require_once __DIR__ . '/config.php';

$user_id = (int)$_SESSION['user_id'];

// Fetch my transactions
$txSql = "SELECT bt.transaction_id, e.equipment_name, bt.borrow_date, bt.return_date, bt.quantity_borrowed, bt.status, bt.purpose
          FROM borrow_transaction bt JOIN equipment e ON e.equipment_id = bt.equipment_id
          WHERE bt.user_id = ? ORDER BY bt.borrow_date DESC LIMIT 100";
$stmt = mysqli_prepare($conn, $txSql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$tx = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Fetch my item requests
$rqSql = "SELECT ir.request_id, e.equipment_name, ir.quantity_requested, ir.request_date, ir.status
          FROM item_request ir JOIN equipment e ON e.equipment_id = ir.equipment_id
          WHERE ir.user_id = ? ORDER BY ir.request_date DESC LIMIT 100";
$stmt = mysqli_prepare($conn, $rqSql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$rq = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

// Fetch equipment for request form
$eq = mysqli_query($conn, "SELECT equipment_id, equipment_name, quantity_available FROM equipment ORDER BY equipment_name");

include __DIR__ . '/includes/student_header.php';
?>
<main class="flex-1">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-6">Student Dashboard</h2>

    <?php if (!empty($_GET['error'])): ?>
      <div class="rounded-md bg-red-50 p-4 mb-6 ring-1 ring-red-200">
        <h3 class="text-sm font-medium text-red-800">Error</h3>
        <p class="mt-1 text-sm text-red-700"><?php echo htmlspecialchars($_GET['error']); ?></p>
      </div>
    <?php endif; ?>
    <?php if (!empty($_GET['success'])): ?>
      <div class="rounded-md bg-green-50 p-4 mb-6 ring-1 ring-green-200">
        <h3 class="text-sm font-medium text-green-800">Success</h3>
        <p class="mt-1 text-sm text-green-700"><?php echo htmlspecialchars($_GET['success']); ?></p>
      </div>
    <?php endif; ?>

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
      <div id="request" class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-sm font-semibold text-gray-900">Request to Borrow</h3>
          <p class="mt-1 text-sm text-gray-500">Submit a borrow request. If unavailable, you can join the waitlist.</p>
        </div>
        <form class="p-4 grid grid-cols-1 gap-3" method="post" action="controller/item_request_create.php">
          <div>
            <label class="block text-sm font-medium text-gray-700">Equipment</label>
            <select name="equipment_id" class="mt-1 block w-full rounded-md border-gray-300" required>
              <option value="" disabled selected>Select equipment</option>
              <?php while ($e = $eq->fetch_assoc()): ?>
                <option value="<?php echo (int)$e['equipment_id']; ?>" data-available="<?php echo (int)$e['quantity_available']; ?>"><?php echo htmlspecialchars($e['equipment_name']); ?> (Available: <?php echo (int)$e['quantity_available']; ?>)</option>
              <?php endwhile; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" name="quantity_requested" min="1" value="1" class="mt-1 block w-full rounded-md border-gray-300" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">If unavailable</label>
            <select name="unavailable_action" class="mt-1 block w-full rounded-md border-gray-300">
              <option value="waitlist">Join waitlist</option>
              <option value="cancel">Cancel request</option>
            </select>
          </div>
          <div class="pt-1 flex items-center gap-3">
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Submit</button>
          </div>
        </form>

        <div class="p-4 border-t border-gray-100">
          <h3 class="text-sm font-semibold text-gray-900">Suggest New Item</h3>
          <form class="mt-2 grid grid-cols-1 gap-3" method="post" action="controller/suggestion_create.php">
            <div>
              <label class="block text-sm font-medium text-gray-700">Item suggestion</label>
              <input type="text" name="suggestion" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Describe the item" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Reason</label>
              <textarea name="reason" rows="3" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Why should we add it?"></textarea>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700">Send Suggestion</button>
          </form>
        </div>
      </div>
      <div class="lg:col-span-2 bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
        <h3 class="text-sm font-semibold text-gray-900 mb-3">My Requests</h3>
        <div class="overflow-x-auto">
          <table id="myReq" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php while ($row = $rq->fetch_assoc()): ?>
              <tr class="hover:bg-gray-50">
                <td class="px-3 py-2 text-sm text-gray-900"><?php echo (int)$row['request_id']; ?></td>
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
    $('#myReq').DataTable({ responsive: true, pageLength: 5, order: [[3, 'desc']] });
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

