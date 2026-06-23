<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_role('student');

$uid = (int) $_SESSION['user_id'];

// Count stats for this student
function count_status($conn, $uid, $status = null) {
    if ($status) {
        $sql = "SELECT COUNT(*) AS c FROM projects WHERE user_id = $uid AND status = '$status'";
    } else {
        $sql = "SELECT COUNT(*) AS c FROM projects WHERE user_id = $uid";
    }
    $r = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($r);
    return (int) $row['c'];
}

$total    = count_status($conn, $uid);
$pending  = count_status($conn, $uid, 'Pending');
$approved = count_status($conn, $uid, 'Approved');
$rejected = count_status($conn, $uid, 'Rejected');

$page_title = "Dashboard";
$active = "dashboard";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="mb-8">
    <h1 class="text-2xl font-bold">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></h1>
    <p class="text-slate-500">Here is an overview of your projects.</p>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <p class="text-sm text-slate-500">Total Projects</p>
        <p class="text-3xl font-bold mt-1"><?php echo $total; ?></p>
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
        <a href="<?php echo base_path(); ?>/student/create-project.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Create New Project</a>
        <a href="<?php echo base_path(); ?>/student/my-projects.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">View My Projects</a>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
