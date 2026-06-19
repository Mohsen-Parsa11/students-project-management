<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_role('admin');

function count_query($conn, $sql) {
    $r = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($r);
    return (int) $row['c'];
}

$totalUsers     = count_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'student'");
$totalProjects  = count_query($conn, "SELECT COUNT(*) AS c FROM projects");
$pending        = count_query($conn, "SELECT COUNT(*) AS c FROM projects WHERE status = 'Pending'");
$approved       = count_query($conn, "SELECT COUNT(*) AS c FROM projects WHERE status = 'Approved'");
$rejected       = count_query($conn, "SELECT COUNT(*) AS c FROM projects WHERE status = 'Rejected'");

$page_title = "Admin Dashboard";
$active = "dashboard";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    <p class="text-slate-500">System overview and statistics.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Students</p>
        <p class="text-3xl font-bold mt-1"><?php echo $totalUsers; ?></p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Total Projects</p>
        <p class="text-3xl font-bold mt-1"><?php echo $totalProjects; ?></p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Pending</p>
        <p class="text-3xl font-bold mt-1 text-amber-600"><?php echo $pending; ?></p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Approved</p>
        <p class="text-3xl font-bold mt-1 text-green-600"><?php echo $approved; ?></p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Rejected</p>
        <p class="text-3xl font-bold mt-1 text-red-600"><?php echo $rejected; ?></p>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl border border-slate-200 p-6">
    <h2 class="font-semibold text-lg">Quick Actions</h2>
    <div class="flex flex-wrap gap-3 mt-4">
        <a href="<?php echo base_path(); ?>/admin/projects.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Manage Projects</a>
        <a href="<?php echo base_path(); ?>/admin/users.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">Manage Users</a>
        <a href="<?php echo base_path(); ?>/admin/export.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">Export CSV</a>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
