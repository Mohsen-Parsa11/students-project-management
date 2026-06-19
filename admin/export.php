<?php
require_once __DIR__ . "/../config/auth.php";
require_once __DIR__ . "/../config/db.php";
require_role('admin');

// Send CSV headers
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=projects_" . date("Y-m-d") . ".csv");

$output = fopen("php://output", "w");

// Column headings
fputcsv($output, ['Project ID', 'Student Name', 'Title', 'Category', 'Status', 'Date']);

$sql = "SELECT p.id, u.fullname, p.title, p.category, p.status, p.created_at
        FROM projects p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.id ASC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $row['id'],
        $row['fullname'],
        $row['title'],
        $row['category'],
        $row['status'],
        date("Y-m-d", strtotime($row['created_at'])),
    ]);
}

fclose($output);
exit;
?>
