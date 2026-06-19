<?php
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";

// Already logged in? go to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: " . base_path() . "/index.php");
    exit;
}

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect + sanitize input
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname'] ?? ''));
    $email    = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    // Basic validation
    if ($fullname === '') $errors[] = "Full name is required.";
    if ($email === '')    $errors[] = "Email is required.";
    if ($password === '') $errors[] = "Password is required.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirm) $errors[] = "Passwords do not match.";

    // Check duplicate email
    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = "Email is already registered.";
        }
    }

    // Insert user (students only)
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (fullname, email, password, role)
                VALUES ('$fullname', '$email', '$hashed', 'student')";
        if (mysqli_query($conn, $sql)) {
            $success = "Account created successfully. You can now login.";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SPMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Create Account</h1>
            <p class="text-slate-500 text-sm mt-1">Register as a student to submit projects.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-4">
                <ul class="list-disc list-inside space-y-1">
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-lg p-3 mb-4">
                <?php echo htmlspecialchars($success); ?>
                <a href="<?php echo base_path(); ?>/auth/login.php" class="font-semibold underline">Login now</a>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Full Name</label>
                <input type="text" name="fullname" value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                <input type="password" name="confirm"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Already have an account?
            <a href="<?php echo base_path(); ?>/auth/login.php" class="text-blue-600 font-medium hover:underline">Login</a>
        </p>
    </div>

</body>
</html>
