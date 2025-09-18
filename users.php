<?php
// Page title for header
$title = "Users";

// DB connection
require_once __DIR__ . '/config.php';

// Fetch all users except admins
$sql = "SELECT user_id, user_type, name, email, contact_number, student_id FROM users WHERE LOWER(user_type) <> 'admin' ORDER BY name";
$result = mysqli_query($conn, $sql);

// Prepare records
$users = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    $error = mysqli_error($conn);
}

include __DIR__ . '/includes/header.php';
?>

<main class="flex-1">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Users</h2>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Database Error</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <p><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg p-4">
            <div class="overflow-x-auto">
                <table id="usersTable" class="min-w-full divide-y divide-gray-200 display nowrap" style="width:100%">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Type</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo (int)$u['user_id']; ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($u['name']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($u['email']); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($u['contact_number'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                        <?php echo htmlspecialchars($u['user_type']); ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($u['student_id'] ?? ''); ?></td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="view_user_transaction.php?user_id=<?php echo (int)$u['user_id']; ?>" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100">View</a>
                                        <a href="user_edit.php?user_id=<?php echo (int)$u['user_id']; ?>" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 hover:bg-amber-100">Edit</a>
                                        <form action="controller/user_delete.php" method="post" onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                            <input type="hidden" name="user_id" value="<?php echo (int)$u['user_id']; ?>">
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
        $('#usersTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50, 100],
            order: [[1, 'asc']],
            columnDefs: [
                { targets: 0, width: 60 },
                { targets: [3,6], orderable: false },
                { targets: -1, searchable: false }
            ]
        });
    });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

