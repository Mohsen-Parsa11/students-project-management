<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";
require_role('student');

$uid = (int) $_SESSION['user_id'];

// Handle delete (only own projects)
if (isset($_GET['delete'])) {
    $del = (int) $_GET['delete'];
    // Find the file first so we can remove it from disk
    $r = mysqli_query($conn, "SELECT file FROM projects WHERE id = $del AND user_id = $uid");
    if ($r && mysqli_num_rows($r) === 1) {
        $row = mysqli_fetch_assoc($r);
        if (!empty($row['file'])) {
            $path = __DIR__ . "/../uploads/" . $row['file'];
            if (file_exists($path)) { unlink($path); }
        }
        mysqli_query($conn, "DELETE FROM projects WHERE id = $del AND user_id = $uid");
    }
    header("Location: " . base_path() . "/student/my-projects.php");
    exit;
}

$result = mysqli_query($conn, "SELECT * FROM projects WHERE user_id = $uid ORDER BY created_at DESC");

$page_title = "My Projects";
$active = "myprojects";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold">My Projects</h1>
        <p class="text-slate-500">All projects you have submitted.</p>
    </div>
    <a href="<?php echo base_path(); ?>/student/create-project.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">New Project</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">File</th>
                    <th class="px-4 py-3 font-medium">Date</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-slate-400">
                            No projects yet. Click "New Project" to add one.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php while ($p = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="px-4 py-3 font-medium"><?php echo htmlspecialchars($p['title']); ?></td>
                            <td class="px-4 py-3"><?php echo htmlspecialchars($p['category']); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium <?php echo status_badge($p['status']); ?>">
                                    <?php echo htmlspecialchars($p['status']); ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (!empty($p['file'])): ?>
                                    <a href="<?php echo base_path(); ?>/uploads/<?php echo htmlspecialchars($p['file']); ?>" target="_blank" class="text-blue-600 hover:underline">View</a>
                                <?php else: ?>
                                    <span class="text-slate-400">None</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-slate-500"><?php echo date("d M Y", strtotime($p['created_at'])); ?></td>
                            <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="<?php echo base_path(); ?>/student/edit-project.php?id=<?php echo $p['id']; ?>" class="text-blue-600 hover:underline">Edit</a>
                                <a href="<?php echo base_path(); ?>/student/my-projects.php?delete=<?php echo $p['id']; ?>"
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

<?php include __DIR__ . "/../includes/footer.php"; ?>
