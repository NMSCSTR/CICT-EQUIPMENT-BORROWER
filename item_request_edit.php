<?php
$title = 'Edit Item Request';
require_once __DIR__ . '/config.php';

$request_id = isset($_GET['request_id']) ? (int)$_GET['request_id'] : 0;
if ($request_id <= 0) { header('Location: item_request.php'); exit; }

$sql = "SELECT request_id, user_id, equipment_id, quantity_requested, request_date, status, approved_by FROM item_request WHERE request_id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $request_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$req = $res ? $res->fetch_assoc() : null;
mysqli_stmt_close($stmt);
if (!$req) { header('Location: item_request.php'); exit; }

// Load users and equipment for selects
$users = [];
$r = mysqli_query($conn, 'SELECT user_id, name FROM users ORDER BY name');
while ($row = $r && mysqli_fetch_assoc($r) ? $row : null) {}
// Correct way to fetch
mysqli_data_seek($r, 0);
while ($row = mysqli_fetch_assoc($r)) { $users[] = $row; }

$equipment = [];
$re = mysqli_query($conn, 'SELECT equipment_id, equipment_name FROM equipment ORDER BY equipment_name');
while ($row = $re && mysqli_fetch_assoc($re) ? $row : null) {}
mysqli_data_seek($re, 0);
while ($row = mysqli_fetch_assoc($re)) { $equipment[] = $row; }

include __DIR__ . '/includes/admin_header.php';
?>
<main class="flex-1">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="item_request.php" class="text-sm text-blue-700 hover:underline">&larr; Back to Item Requests</a>
    <h2 class="text-xl font-semibold text-gray-900 mt-2">Edit Item Request</h2>

    <?php $err = isset($_GET['error']) ? (string)$_GET['error'] : ''; ?>
    <?php if ($err !== ''): ?>
      <div class="mt-4 rounded-md bg-red-50 p-4 ring-1 ring-red-200">
        <h3 class="text-sm font-semibold text-red-800">There was a problem</h3>
        <p class="mt-1 text-sm text-red-700"><?php echo htmlspecialchars($err); ?></p>
      </div>
    <?php endif; ?>

    <form class="mt-6" action="controller/item_request_update.php" method="post">
      <input type="hidden" name="request_id" value="<?php echo (int)$req['request_id']; ?>">

      <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-sm font-semibold text-gray-900">Request Details</h3>
          <p class="mt-1 text-sm text-gray-500">Update the item request information.</p>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Requester</label>
            <select name="user_id" class="mt-1 block w-full rounded-md border-gray-300">
              <?php foreach ($users as $u): ?>
                <option value="<?php echo (int)$u['user_id']; ?>" <?php echo (int)$req['user_id'] === (int)$u['user_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($u['name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Equipment</label>
            <select name="equipment_id" class="mt-1 block w-full rounded-md border-gray-300">
              <?php foreach ($equipment as $e): ?>
                <option value="<?php echo (int)$e['equipment_id']; ?>" <?php echo (int)$req['equipment_id'] === (int)$e['equipment_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($e['equipment_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Quantity</label>
            <input type="number" name="quantity_requested" min="1" value="<?php echo (int)$req['quantity_requested']; ?>" class="mt-1 block w-full rounded-md border-gray-300">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <input type="text" name="status" value="<?php echo htmlspecialchars($req['status']); ?>" class="mt-1 block w-full rounded-md border-gray-300">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Approved By (User ID)</label>
            <input type="number" name="approved_by" value="<?php echo (int)$req['approved_by']; ?>" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Optional">
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 rounded-b-lg flex items-center gap-3">
          <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Save Changes</button>
          <a href="item_request.php" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

