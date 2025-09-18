<?php
$title = 'Edit Equipment';
require_once __DIR__ . '/config.php';

$equipment_id = isset($_GET['equipment_id']) ? (int)$_GET['equipment_id'] : 0;
if ($equipment_id <= 0) { header('Location: equipment.php'); exit; }

$stmt = mysqli_prepare($conn, 'SELECT equipment_id, equipment_name, description, quantity_total, quantity_available, equipment_condition FROM equipment WHERE equipment_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $equipment_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$eq = $res ? $res->fetch_assoc() : null;
mysqli_stmt_close($stmt);
if (!$eq) { header('Location: equipment.php'); exit; }

include __DIR__ . '/includes/header.php';
?>
<main class="flex-1">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="equipment.php" class="text-sm text-blue-700 hover:underline">&larr; Back to Equipment</a>
    <h2 class="text-xl font-semibold text-gray-900 mt-2">Edit Equipment</h2>

    <?php $err = isset($_GET['error']) ? (string)$_GET['error'] : ''; ?>
    <?php if ($err !== ''): ?>
      <div class="mt-4 rounded-md bg-red-50 p-4 ring-1 ring-red-200">
        <h3 class="text-sm font-semibold text-red-800">There was a problem</h3>
        <p class="mt-1 text-sm text-red-700"><?php echo htmlspecialchars($err); ?></p>
      </div>
    <?php endif; ?>

    <form class="mt-6" action="controller/equipment_update.php" method="post">
      <input type="hidden" name="equipment_id" value="<?php echo (int)$eq['equipment_id']; ?>">

      <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-sm font-semibold text-gray-900">Equipment Details</h3>
          <p class="mt-1 text-sm text-gray-500">Update the equipment information.</p>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
            <input type="text" name="equipment_name" value="<?php echo htmlspecialchars($eq['equipment_name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"><?php echo htmlspecialchars($eq['description']); ?></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Total Quantity</label>
            <input type="number" name="quantity_total" min="0" value="<?php echo (int)$eq['quantity_total']; ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Available Quantity</label>
            <input type="number" name="quantity_available" min="0" value="<?php echo (int)$eq['quantity_available']; ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Condition</label>
            <input type="text" name="equipment_condition" value="<?php echo htmlspecialchars($eq['equipment_condition']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 rounded-b-lg flex items-center gap-3">
          <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Save Changes</button>
          <a href="equipment.php" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

