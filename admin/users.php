<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_role('admin');

// Handle delete user (cannot delete yourself)
if (isset($_GET['delete'])) {
    $del = (int) $_GET['delete'];
    if ($del !== (int) $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $del");
    }
    header("Location: " . base_path() . "/admin/users.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");

$page_title = "Manage Users";
$active = "users";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Manage Users</h1>
    <p class="text-slate-500">All registered users in the system.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">ID</th>
                    <th class="px-4 py-3 font-medium">Name</th>
                    <th class="px-4 py-3 font-medium">Email</th>
                    <th class="px-4 py-3 font-medium">Role</th>
                    <th class="px-4 py-3 font-medium">Joined</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php while ($u = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="px-4 py-3 text-slate-500">#<?php echo $u['id']; ?></td>
                        <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($u['fullname']); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($u['email']); ?></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo $u['role'] === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700'; ?>">
                                <?php echo htmlspecialchars($u['role']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500"><?php echo date("d M Y", strtotime($u['created_at'])); ?></td>
                        <td class="px-4 py-3 text-right">
                            <?php if ((int) $u['id'] !== (int) $_SESSION['user_id']): ?>
                                <a href="<?php echo base_path(); ?>/admin/users.php?delete=<?php echo $u['id']; ?>"
                                    onclick="return confirm('Delete this user and all their projects?');"
                                    class="text-red-600 hover:underline">Delete</a>
                            <?php else: ?>
                                <span class="text-slate-400 text-xs">You</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
