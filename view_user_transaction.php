<?php
// view_user_transaction.php
$title = "User Transactions";
require_once __DIR__ . '/config.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id <= 0) {
    header('Location: users.php');
    exit;
}

// Fetch user
$stmt = mysqli_prepare($conn, 'SELECT user_id, name, email FROM users WHERE user_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_stmt_get_result($stmt)->fetch_assoc();
mysqli_stmt_close($stmt);
if (!$user) {
    header('Location: users.php');
    exit;
}

// Fetch transactions
$sql = "SELECT bt.transaction_id, e.equipment_name, bt.borrow_date, bt.return_date, bt.quantity_borrowed, bt.status, bt.purpose
        FROM borrow_transaction bt
        JOIN equipment e ON e.equipment_id = bt.equipment_id
        WHERE bt.user_id = ?
        ORDER BY bt.borrow_date DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$transactions = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

include __DIR__ . '/includes/header.php';
?>
<main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="users.php" class="text-sm text-blue-700 hover:underline">&larr; Back to Users</a>
            <h2 class="text-xl font-semibold text-gray-900 mt-2">Borrow Transactions for <?php echo htmlspecialchars($user['name']); ?></h2>
            <p class="text-sm text-gray-600">Email: <?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
            <div class="overflow-x-auto">
                <table id="txTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Txn ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrowed</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Returned</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($tx = $transactions->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$tx['transaction_id']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($tx['equipment_name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['borrow_date']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['return_date'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo (int)$tx['quantity_borrowed']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-50 text-green-700"><?php echo htmlspecialchars($tx['status'] ?? ''); ?></span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($tx['purpose'] ?? ''); ?></td>
                            </tr>
                        <?php endwhile; ?>
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
        $('#txTable').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[2, 'desc']],
            columnDefs: [
                { targets: 0, width: 80 }
            ]
        });
    });
    </script>

<?php include __DIR__ . '/includes/footer.php'; ?>

