<?php
session_name('SPMS_ADMIN');
session_start();
// If already logged in, send to the right dashboard
require_once __DIR__ . "/config/functions.php";
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header("Location: " . base_path() . "/admin/dashboard.php");
    } else {
        $_SESSION = [];
        session_destroy();
        header("Location: " . base_path() . "/auth/login.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="bg-white scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMS - Student Project Management System</title>
    <meta name="description" content="A simple system for students to submit and track university semester projects.">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-800">

    <!-- Navbar -->
    <header class="border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold">S</span>
                <span class="font-bold text-lg">SPMS</span>
            </div>
            <nav class="flex items-center gap-3">
                <a href="<?php echo base_path(); ?>/auth/login.php" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700">Admin Login</a>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 py-20 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-balance leading-tight">
                Student Project Management System
            </h1>
            <p class="mt-5 text-lg text-slate-600 max-w-2xl mx-auto text-pretty">
                A simple admin panel for reviewing student semester projects, managing users,
                approving submissions, and exporting project records.
            </p>
            <div class="mt-8 flex items-center justify-center gap-4">
                <a href="<?php echo base_path(); ?>/auth/login.php" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">Admin Login</a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="max-w-6xl mx-auto px-4 py-16">
        <h2 class="text-2xl font-bold text-center">Features</h2>
        <p class="text-center text-slate-600 mt-2">Everything you need to manage semester projects.</p>

        <div class="grid gap-6 md:grid-cols-3 mt-10">
            <div class="border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold mb-4">1</div>
                <h3 class="font-semibold text-lg">Review Projects</h3>
                <p class="text-slate-600 text-sm mt-2">Admins can view submitted projects, uploaded files, descriptions, categories, and student details.</p>
            </div>
            <div class="border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center font-bold mb-4">2</div>
                <h3 class="font-semibold text-lg">Update Status</h3>
                <p class="text-slate-600 text-sm mt-2">Every project can be marked Pending, Approved, or Rejected with the same clear workflow as before.</p>
            </div>
            <div class="border border-slate-200 rounded-xl p-6">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center font-bold mb-4">3</div>
                <h3 class="font-semibold text-lg">Admin Control</h3>
                <p class="text-slate-600 text-sm mt-2">Manage users and projects, approve or reject submissions, and export records as CSV.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 mt-10">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center text-sm text-slate-500">
            &copy; <?php echo date("Y"); ?> Student Project Management System. University Semester Project.
        </div>
    </footer>

</body>
</html>
