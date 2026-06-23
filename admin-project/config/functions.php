<?php
// Shared helper functions

function base_path() {
    if (!isset($_SERVER['SCRIPT_NAME'])) return '';
    $parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    if (count($parts) === 0) return '';
    $projectRoot = basename(dirname(__DIR__));
    foreach ($parts as $index => $part) {
        if ($part === $projectRoot) {
            return '/' . implode('/', array_slice($parts, 0, $index + 1));
        }
    }
    // If the first segment looks like a filename (contains a dot), assume web root
    if (strpos($parts[0], '.') !== false) return '';
    return '/' . $parts[0];
}

function shared_base_path() {
    $base = base_path();
    $projectRoot = basename(dirname(__DIR__));
    if (substr($base, -strlen('/' . $projectRoot)) === '/' . $projectRoot) {
        return substr($base, 0, -strlen('/' . $projectRoot));
    }
    return $base;
}

function uploads_dir() {
    $shared = realpath(__DIR__ . "/../../uploads");
    if ($shared !== false) {
        return $shared . DIRECTORY_SEPARATOR;
    }
    return __DIR__ . "/../uploads/";
}

function upload_url($filename) {
    $shared = realpath(__DIR__ . "/../../uploads");
    $base = $shared !== false ? shared_base_path() : base_path();
    return $base . "/uploads/" . rawurlencode($filename);
}
function handle_upload($fileInput) {
    // No file chosen -> allowed (file is optional on edit)
    if (!isset($fileInput) || $fileInput['error'] === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true, 'filename' => ''];
    }

    if ($fileInput['error'] !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'File upload failed. Please try again.'];
    }

    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    $ext = strtolower(pathinfo($fileInput['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['ok' => false, 'error' => 'Only PDF, JPG, and PNG files are allowed.'];
    }
    if ($fileInput['size'] > $maxSize) {
        return ['ok' => false, 'error' => 'File is too large. Maximum size is 5 MB.'];
    }

    // Unique filename: time + random number
    $newName = time() . "_" . rand(1000, 9999) . "." . $ext;
    $uploadDir = uploads_dir();
    $target = $uploadDir . $newName;

    if (!move_uploaded_file($fileInput['tmp_name'], $target)) {
        return ['ok' => false, 'error' => 'Could not save the uploaded file.'];
    }

    return ['ok' => true, 'filename' => $newName];
}

// Returns a Tailwind badge class for a status value.
function status_badge($status) {
    switch ($status) {
        case 'Approved': return 'bg-green-100 text-green-700';
        case 'Rejected': return 'bg-red-100 text-red-700';
        default:         return 'bg-amber-100 text-amber-700';
    }
}
?>
