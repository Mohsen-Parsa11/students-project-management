<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";
require_role('student');

$uid = (int) $_SESSION['user_id'];
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));

    if ($title === '')    $errors[] = "Project title is required.";
    if ($category === '') $errors[] = "Category is required.";

    // Handle file upload
    $filename = '';
    if (empty($errors)) {
        $up = handle_upload($_FILES['file'] ?? null);
        if (!$up['ok']) {
            $errors[] = $up['error'];
        } else {
            $filename = $up['filename'];
        }
    }

    if (empty($errors)) {
        $sql = "INSERT INTO projects (user_id, title, description, category, file, status)
                VALUES ($uid, '$title', '$description', '$category', '$filename', 'Pending')";
        if (mysqli_query($conn, $sql)) {
            header("Location: " . base_path() . "/student/my-projects.php");
            exit;
        } else {
            $errors[] = "Could not save the project. Please try again.";
        }
    }
}

$page_title = "Create Project";
$active = "create";
include __DIR__ . "/../includes/header.php";
include __DIR__ . "/../includes/sidebar.php";
?>

<div class="max-w-2xl">
    <h1 class="text-2xl font-bold mb-1">Create Project</h1>
    <p class="text-slate-500 mb-6">Submit a new semester project for review.</p>

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
            <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Category</label>
            <input type="text" name="category" placeholder="e.g. Web, Mobile, AI"
                value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <textarea name="description" rows="4"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Project File (PDF, JPG, PNG)</label>
            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
            <p class="text-xs text-slate-400 mt-1">Maximum size: 5 MB.</p>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Submit Project</button>
            <a href="<?php echo base_path(); ?>/student/my-projects.php" class="px-4 py-2 border border-slate-300 hover:bg-slate-50 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
