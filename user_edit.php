<?php
$title = 'Edit User';
require_once __DIR__ . '/config.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id <= 0) { header('Location: users.php'); exit; }

$stmt = mysqli_prepare($conn, 'SELECT user_id, user_type, name, email, contact_number, student_id FROM users WHERE user_id = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = $res ? $res->fetch_assoc() : null;
mysqli_stmt_close($stmt);
if (!$user) { header('Location: users.php'); exit; }

include __DIR__ . '/includes/header.php';
?>
<main class="flex-1">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="users.php" class="text-sm text-blue-700 hover:underline">&larr; Back to Users</a>
    <h2 class="text-xl font-semibold text-gray-900 mt-2">Edit User</h2>

    <?php
      $err = isset($_GET['error']) ? trim((string)$_GET['error']) : '';
      $ok = isset($_GET['success']) ? trim((string)$_GET['success']) : '';
    ?>
    <?php if ($err !== ''): ?>
      <div class="mt-4 rounded-md bg-red-50 p-4 ring-1 ring-red-200">
        <div class="flex">
          <div>
            <h3 class="text-sm font-semibold text-red-800">There was a problem</h3>
            <div class="mt-1 text-sm text-red-700"><?php echo htmlspecialchars($err); ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($ok !== ''): ?>
      <div class="mt-4 rounded-md bg-green-50 p-4 ring-1 ring-green-200">
        <div class="flex">
          <div>
            <h3 class="text-sm font-semibold text-green-800">Saved</h3>
            <div class="mt-1 text-sm text-green-700"><?php echo htmlspecialchars($ok); ?></div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <form class="mt-6" action="controller/user_update.php" method="post">
      <input type="hidden" name="user_id" value="<?php echo (int)$user['user_id']; ?>">

      <div class="bg-white shadow-sm ring-1 ring-gray-200 rounded-lg">
        <div class="p-4 border-b border-gray-100">
          <h3 class="text-sm font-semibold text-gray-900">User Details</h3>
          <p class="mt-1 text-sm text-gray-500">Update the user’s profile information.</p>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">User Type</label>
            <select name="user_type" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
          <?php $types = ['student','instructor','staff','admin']; foreach ($types as $t): ?>
            <option value="<?php echo $t; ?>" <?php echo strtolower($user['user_type']) === $t ? 'selected' : ''; ?>><?php echo ucfirst($t); ?></option>
          <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
            <p class="mt-1 text-xs text-gray-500">We’ll use this email for account notifications.</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Contact Number</label>
            <input type="text" name="contact_number" value="<?php echo htmlspecialchars($user['contact_number']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. 0917 123 4567">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Student ID</label>
            <input type="text" name="student_id" value="<?php echo htmlspecialchars($user['student_id']); ?>" class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. 2025-12345">
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 rounded-b-lg flex items-center gap-3">
          <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">Save Changes</button>
          <a href="users.php" class="text-sm text-gray-600 hover:text-gray-800">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

