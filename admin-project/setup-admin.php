<?php
// ============================================
// One-time admin setup script.
// Visit http://localhost/spms/setup-admin.php in your browser,
// then DELETE this file for security.
// ============================================
require_once __DIR__ . "/config/db.php";

$email    = "admin@spms.com";
$password = "admin123";
$fullname = "System Admin";

// Skip if admin already exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
if (mysqli_num_rows($check) > 0) {
    echo "Admin already exists. You can login with $email / $password.<br>";
    echo "Please DELETE setup-admin.php now.";
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO users (fullname, email, password, role)
        VALUES ('$fullname', '$email', '$hashed', 'admin')";

if (mysqli_query($conn, $sql)) {
    echo "Admin account created!<br>";
    echo "Email: $email<br>";
    echo "Password: $password<br><br>";
    echo "IMPORTANT: Delete setup-admin.php now, then go to <a href='auth/login.php'>login</a>.";
} else {
    echo "Error creating admin: " . mysqli_error($conn);
}
?>
