<?php
$title = 'Borrow Transactions';
require_once __DIR__ . '/config.php';

$sql = "SELECT bt.transaction_id,
               u.name AS user_name,
               u.email AS user_email,
               e.equipment_name,
               bt.borrow_date,
               bt.return_date,
               bt.quantity_borrowed,
               bt.status,
               bt.expected_return_date,
               bt.purpose
        FROM borrow_transaction bt
        JOIN users u ON u.user_id = bt.user_id
        JOIN equipment e ON e.equipment_id = bt.equipment_id
        ORDER BY bt.borrow_date DESC";

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
            <h2 class="text-xl font-semibold text-gray-900">Borrow Transactions</h2>
        </div>

        <?php if (!empty($error)): ?>
            <div class="rounded-md bg-red-50 p-4 mb-6 ring-1 ring-red-200">
                <h3 class="text-sm font-medium text-red-800">Error</h3>
                <p class="mt-1 text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
            <div class="overflow-x-auto">
                <table id="btTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Txn ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expected Return</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($rows as $tx): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$tx['transaction_id']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($tx['user_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['user_email']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($tx['equipment_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['borrow_date']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['return_date'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo (int)$tx['quantity_borrowed']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['expected_return_date'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700"><?php echo htmlspecialchars($tx['status'] ?? ''); ?></span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 max-w-xs truncate" title="<?php echo htmlspecialchars($tx['purpose'] ?? ''); ?>"><?php echo htmlspecialchars($tx['purpose'] ?? ''); ?></td>
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
        $('#btTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[4, 'desc']],
            columnDefs: [
                { targets: 0, width: 80 },
                { targets: 8, orderable: false }
            ]
        });
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

