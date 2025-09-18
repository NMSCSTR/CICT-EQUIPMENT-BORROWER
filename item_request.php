<?php
$title = 'Item Requests';
require_once __DIR__ . '/config.php';

$sql = "SELECT ir.request_id,
               u.name AS requester_name,
               u.email AS requester_email,
               e.equipment_name,
               ir.quantity_requested,
               ir.request_date,
               ir.status,
               ir.approved_by,
               au.name AS approver_name
        FROM item_request ir
        JOIN users u ON u.user_id = ir.user_id
        JOIN equipment e ON e.equipment_id = ir.equipment_id
        LEFT JOIN users au ON au.user_id = ir.approved_by
        ORDER BY ir.request_date DESC";

$result = mysqli_query($conn, $sql);
$rows = [];
if ($result) {
    while ($r = mysqli_fetch_assoc($result)) { $rows[] = $r; }
} else {
    $error = mysqli_error($conn);
}

include __DIR__ . '/includes/admin_header.php';
?>
<main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Item Requests</h2>
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
                <table id="reqTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Req ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved By</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rows as $r): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$r['request_id']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($r['requester_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($r['requester_email']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($r['equipment_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo (int)$r['quantity_requested']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($r['request_date']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700"><?php echo htmlspecialchars($r['status'] ?? ''); ?></span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($r['approver_name'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <?php $isApproved = strtolower((string)$r['status']) === 'approved'; ?>
                                        <?php if (!$isApproved): ?>
                                        <form action="controller/item_request_approve.php" method="post" onsubmit="return confirm('Approve this request and deduct availability?');">
                                            <input type="hidden" name="request_id" value="<?php echo (int)$r['request_id']; ?>">
                                            <button type="submit" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-50 text-green-700 hover:bg-green-100">Approve</button>
                                        </form>
                                        <?php endif; ?>
                                        <a href="item_request_edit.php?request_id=<?php echo (int)$r['request_id']; ?>" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100">Edit</a>
                                        <form action="controller/item_request_delete.php" method="post" onsubmit="return confirm('Delete this request?');">
                                            <input type="hidden" name="request_id" value="<?php echo (int)$r['request_id']; ?>">
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
<script>
    $(function(){
        $('#reqTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[5, 'desc']],
            columnDefs: [
                { targets: 0, width: 80 },
                { targets: -1, orderable: false, searchable: false }
            ]
        });
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

