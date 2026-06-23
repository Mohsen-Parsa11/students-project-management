<?php
// Shared sidebar. Expects $active (string) and a started session.
if (!isset($active)) { $active = ""; }
$role = 'admin';
$name = $_SESSION['fullname'] ?? 'User';

function nav_class($key, $active) {
    $base = "block px-4 py-2 rounded-lg text-sm font-medium transition";
    if ($key === $active) {
        return $base . " bg-blue-600 text-white";
    }
    return $base . " text-slate-300 hover:bg-slate-700 hover:text-white";
}
?>
<aside class="w-64 bg-slate-900 text-white flex flex-col shrink-0">
    <div class="px-6 py-5 border-b border-slate-700">
        <h1 class="text-xl font-bold">SPMS</h1>
        <p class="text-xs text-slate-400 mt-1">Project Manager</p>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">
        <a href="<?php echo base_path(); ?>/admin/dashboard.php" class="<?php echo nav_class('dashboard', $active); ?>">Dashboard</a>
        <a href="<?php echo base_path(); ?>/admin/projects.php" class="<?php echo nav_class('projects', $active); ?>">Manage Projects</a>
        <a href="<?php echo base_path(); ?>/admin/users.php" class="<?php echo nav_class('users', $active); ?>">Manage Users</a>
        <a href="<?php echo base_path(); ?>/admin/export.php" class="<?php echo nav_class('export', $active); ?>">Export CSV</a>
    </nav>

    <div class="px-4 py-4 border-t border-slate-700">
        <p class="text-sm font-medium truncate"><?php echo htmlspecialchars($name); ?></p>
        <p class="text-xs text-slate-400 capitalize mb-3"><?php echo htmlspecialchars($role); ?></p>
        <a href="<?php echo base_path(); ?>/auth/logout.php" class="block text-center w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded-lg">Logout</a>
    </div>
</aside>

<main class="flex-1 p-6 lg:p-8 overflow-x-auto">
