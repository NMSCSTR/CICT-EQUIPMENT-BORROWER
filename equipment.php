<?php
$title = 'Equipment';
require_once __DIR__ . '/config.php';

$sql = "SELECT equipment_id, equipment_name, description, quantity_total, quantity_available, equipment_condition FROM equipment ORDER BY equipment_name";
$result = mysqli_query($conn, $sql);
$equipment = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) { $equipment[] = $row; }
} else {
    $error = mysqli_error($conn);
}

include __DIR__ . '/includes/admin_header.php';
?>
<main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Equipment</h2>
            <button type="button" id="openAddModalBtn" class="inline-flex items-center px-3 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Add Equipment</button>
        </div>

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

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
            <div class="overflow-x-auto">
                <table id="equipmentTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($equipment as $e): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$e['equipment_id']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($e['equipment_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 max-w-md truncate" title="<?php echo htmlspecialchars($e['description']); ?>"><?php echo htmlspecialchars($e['description']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo (int)$e['quantity_total']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo (int)$e['quantity_available']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-50 text-slate-700"><?php echo htmlspecialchars($e['equipment_condition']); ?></span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="equipment_edit.php?equipment_id=<?php echo (int)$e['equipment_id']; ?>" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100">Edit</a>
                                        <form action="controller/equipment_delete.php" method="post" onsubmit="return confirm('Delete this equipment?');">
                                            <input type="hidden" name="equipment_id" value="<?php echo (int)$e['equipment_id']; ?>">
                                            <button type="submit" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        

    </div>
</main>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<!-- Add Equipment Modal -->
<div id="addModal" class="fixed inset-0 z-50 hidden" aria-labelledby="add-equipment-title" role="dialog" aria-modal="true">
  <div class="flex min-h-screen items-center justify-center p-4">
    <div class="fixed inset-0 bg-black/40" id="addModalBackdrop"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-lg shadow-lg ring-1 ring-gray-200">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 id="add-equipment-title" class="text-base font-semibold text-gray-900">Add Equipment</h3>
        <button type="button" id="closeAddModalBtn" class="text-gray-500 hover:text-gray-700">✕</button>
      </div>
      <form id="addEquipmentForm" class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4" method="post" action="controller/equipment_create.php">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
          <input type="text" name="equipment_name" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Total Quantity</label>
          <input type="number" name="quantity_total" min="0" value="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Available Quantity</label>
          <input type="number" name="quantity_available" min="0" value="0" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Condition</label>
          <input type="text" name="equipment_condition" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Good, Needs repair">
        </div>
        <div class="md:col-span-2 flex items-center gap-3 pt-2">
          <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Save</button>
          <button type="button" id="cancelAddModalBtn" class="text-sm text-gray-600 hover:text-gray-800">Cancel</button>
        </div>
      </form>
    </div>
  </div>
  </div>
<script>
    $(function(){
        $('#equipmentTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[1, 'asc']],
            columnDefs: [
                { targets: 0, width: 60 },
                { targets: -1, orderable: false, searchable: false }
            ]
        });
        const modal = document.getElementById('addModal');
        const openBtn = document.getElementById('openAddModalBtn');
        const closeBtn = document.getElementById('closeAddModalBtn');
        const cancelBtn = document.getElementById('cancelAddModalBtn');
        const backdrop = document.getElementById('addModalBackdrop');
        function openModal(){ modal.classList.remove('hidden'); }
        function closeModal(){ modal.classList.add('hidden'); document.getElementById('addEquipmentForm').reset(); }
        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        if (backdrop) backdrop.addEventListener('click', closeModal);
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

