<?php
session_name('SPMS_STUDENT');
session_start();
require_once __DIR__ . "/../config/db.php";
require_once __DIR__ . "/../config/functions.php";

// Already logged in? go to dashboard
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['role'] ?? '') === 'student') {
        header("Location: " . base_path() . "/student/dashboard.php");
    } else {
        $_SESSION = [];
        session_destroy();
        header("Location: " . base_path() . "/auth/login.php");
    }
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = "Email and password are required.";
    }

    if (empty($errors)) {
        $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                if ($user['role'] !== 'student') {
                    $errors[] = "This login is for students only.";
                } else {
                // Store session
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role']     = $user['role'];

                header("Location: " . base_path() . "/student/dashboard.php");
                exit;
                }
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SPMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Welcome Back</h1>
            <p class="text-slate-500 text-sm mt-1">Login to your SPMS student account.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg p-3 mb-4">
                <?php foreach ($errors as $e): ?>
                    <p><?php echo htmlspecialchars($e); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
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
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Don't have an account?
            <a href="<?php echo base_path(); ?>/auth/register.php" class="text-blue-600 font-medium hover:underline">Register</a>
        </p>

        <p class="text-center text-xs text-slate-400 mt-4">
            Student accounts can be created from the register page.
        </p>
    </div>

</body>
</html>
