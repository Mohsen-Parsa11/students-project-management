<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";
require_role('student');

$uid = (int) $_SESSION['user_id'];
$errors = [];

// Get project id
$id = (int) ($_GET['id'] ?? 0);
$result = mysqli_query($conn, "SELECT * FROM projects WHERE id = $id AND user_id = $uid LIMIT 1");
if (!$result || mysqli_num_rows($result) !== 1) {
    header("Location: " . base_path() . "/student/my-projects.php");
    exit;
}
$project = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));

    if ($title === '')    $errors[] = "Project title is required.";
    if ($category === '') $errors[] = "Category is required.";

    // Keep old file unless a new one is uploaded
    $filename = $project['file'];
    if (empty($errors)) {
        $up = handle_upload($_FILES['file'] ?? null);
        if (!$up['ok']) {
            $errors[] = $up['error'];
        } elseif ($up['filename'] !== '') {
            // Remove old file from disk
            if (!empty($project['file'])) {
                $oldPath = __DIR__ . "/../uploads/" . $project['file'];
                if (file_exists($oldPath)) { unlink($oldPath); }
            }
            $filename = $up['filename'];
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE projects
                SET title = '$title', description = '$description', category = '$category', file = '$filename'
                WHERE id = $id AND user_id = $uid";
        if (mysqli_query($conn, $sql)) {
            header("Location: " . base_path() . "/student/my-projects.php");
            exit;
        } else {
            $errors[] = "Could not update the project. Please try again.";
        }
    }
    // Refresh local copy for the form
    $project['title'] = $title;
    $project['description'] = $description;
    $project['category'] = $category;
}

$page_title = "Edit Project";
$active = "myprojects";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-1">Edit Project</h1>
    <p class="text-slate-500 mb-6">Update your project details.</p>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-4">
            <ul class="list-disc list-inside space-y-1">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data"
        class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($project['category']); ?>"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="4"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($project['description']); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Replace File (optional)</label>
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
            <?php if (!empty($project['file'])): ?>
                <p class="text-xs text-slate-500 mt-1">
                    Current file:
                    <a href="<?php echo base_path(); ?>/uploads/<?php echo htmlspecialchars($project['file']); ?>" target="_blank" class="text-blue-600 hover:underline">View</a>
                </p>
            <?php endif; ?>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Update Project</button>
            <a href="<?php echo base_path(); ?>/student/my-projects.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
