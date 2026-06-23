<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";
require_role('admin');

// ---- Handle approve / reject ----
if (isset($_GET['action'], $_GET['id'])) {
    $pid = (int) $_GET['id'];
    $action = $_GET['action'];
    if ($action === 'approve') {
        mysqli_query($conn, "UPDATE projects SET status = 'Approved' WHERE id = $pid");
    } elseif ($action === 'reject') {
        mysqli_query($conn, "UPDATE projects SET status = 'Rejected' WHERE id = $pid");
    }
    header("Location: " . base_path() . "/admin/projects.php");
    exit;
}

// ---- Handle delete ----
if (isset($_GET['delete'])) {
    $del = (int) $_GET['delete'];
    $r = mysqli_query($conn, "SELECT file FROM projects WHERE id = $del");
    if ($r && mysqli_num_rows($r) === 1) {
        $row = mysqli_fetch_assoc($r);
        if (!empty($row['file'])) {
            $path = uploads_dir() . $row['file'];
            if (file_exists($path)) { unlink($path); }
        }
        mysqli_query($conn, "DELETE FROM projects WHERE id = $del");
    }
    header("Location: " . base_path() . "/admin/projects.php");
    exit;
}

// ---- Handle edit form submit ----
$editProject = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {
    $eid         = (int) $_POST['edit_id'];
    $title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $status      = mysqli_real_escape_string($conn, $_POST['status'] ?? 'Pending');
    mysqli_query($conn, "UPDATE projects
        SET title = '$title', category = '$category', description = '$description', status = '$status'
        WHERE id = $eid");
    header("Location: " . base_path() . "/admin/projects.php");
    exit;
}

// Load a project into edit mode
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    $r = mysqli_query($conn, "SELECT * FROM projects WHERE id = $eid LIMIT 1");
    if ($r && mysqli_num_rows($r) === 1) {
        $editProject = mysqli_fetch_assoc($r);
    }
}

// ---- Pagination ----
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$countRes = mysqli_query($conn, "SELECT COUNT(*) AS c FROM projects");
$totalRows = (int) mysqli_fetch_assoc($countRes)['c'];
$totalPages = max(1, ceil($totalRows / $perPage));

$sql = "SELECT p.*, u.fullname
        FROM projects p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
        LIMIT $perPage OFFSET $offset";
$result = mysqli_query($conn, $sql);

$page_title = "Manage Projects";
$active = "projects";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">Manage Projects</h1>
        <p class="text-slate-500">Review, edit, and approve student projects.</p>
    </div>
    <a href="<?php echo base_path(); ?>/admin/export.php" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium">Export CSV</a>
</div>

<?php if ($editProject): ?>
    <!-- Edit form -->
    <div class="bg-white rounded-xl border border-blue-200 p-6 mb-6 max-w-2xl">
        <h2 class="font-semibold text-lg mb-4">Edit Project #<?php echo $editProject['id']; ?></h2>
        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="edit_id" value="<?php echo $editProject['id']; ?>">
            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($editProject['title']); ?>"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Category</label>
                <input type="text" name="category" value="<?php echo htmlspecialchars($editProject['category']); ?>"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($editProject['description']); ?></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded-lg px-3 py-2">
                    <?php foreach (['Pending', 'Approved', 'Rejected'] as $s): ?>
                        <option value="<?php echo $s; ?>" <?php echo $editProject['status'] === $s ? 'selected' : ''; ?>><?php echo $s; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Save Changes</button>
                <a href="<?php echo base_path(); ?>/admin/projects.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">ID</th>
                    <th class="px-4 py-3 font-medium">Student</th>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">File</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr><td colspan="7" class="px-4 py-8 text-center text-slate-400">No projects found.</td></tr>
                <?php else: ?>
                    <?php while ($p = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="px-4 py-3 text-slate-500">#<?php echo $p['id']; ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($p['fullname']); ?></td>
                            <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($p['title']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($p['category']); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo status_badge($p['status']); ?>">
                                    <?php echo htmlspecialchars($p['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (!empty($p['file'])): ?>
                                    <a href="<?php echo upload_url($p['file']); ?>" target="_blank" class="text-blue-600 hover:underline">View</a>
                                <?php else: ?>
                                    <span class="text-slate-400">None</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="<?php echo base_path(); ?>/admin/projects.php?action=approve&id=<?php echo $p['id']; ?>" class="text-green-600 hover:underline">Approve</a>
                                <a href="<?php echo base_path(); ?>/admin/projects.php?action=reject&id=<?php echo $p['id']; ?>" class="text-amber-600 hover:underline">Reject</a>
                                <a href="<?php echo base_path(); ?>/admin/projects.php?edit=<?php echo $p['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                                <a href="<?php echo base_path(); ?>/admin/projects.php?delete=<?php echo $p['id']; ?>"
                                    onclick="return confirm('Delete this project?');"
                                    class="text-red-600 hover:underline">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="flex items-center justify-between mt-4">
    <p class="text-sm text-slate-500">Page <?php echo $page; ?> of <?php echo $totalPages; ?></p>
    <div class="flex gap-2">
        <?php if ($page > 1): ?>
            <a href="<?php echo base_path(); ?>/admin/projects.php?page=<?php echo $page - 1; ?>" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm hover:bg-white">Previous</a>
        <?php else: ?>
            <span class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm text-slate-300">Previous</span>
        <?php endif; ?>

        <?php if ($page < $totalPages): ?>
            <a href="<?php echo base_path(); ?>/admin/projects.php?page=<?php echo $page + 1; ?>" class="px-3 py-1.5 border border-slate-300 rounded-lg text-sm hover:bg-white">Next</a>
        <?php else: ?>
            <span class="px-3 py-1.5 border border-slate-200 rounded-lg text-sm text-slate-300">Next</span>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
