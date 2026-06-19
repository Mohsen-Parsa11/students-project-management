<?php
// ============================================
// Shared helper functions
// ============================================

// Returns the app base path (e.g. '/student-project-management' when deployed
// in a subfolder). Returns an empty string when the app is in web root.
function base_path() {
    if (!isset($_SERVER['SCRIPT_NAME'])) return '';
    $parts = explode('/', trim($_SERVER['SCRIPT_NAME'], '/'));
    if (count($parts) === 0) return '';
    // If the first segment looks like a filename (contains a dot), assume web root
    if (strpos($parts[0], '.') !== false) return '';
    return '/' . $parts[0];
}


// Handles a file upload from $_FILES.
// Returns: ['ok' => true, 'filename' => '...'] on success
//          ['ok' => false, 'error' => '...']   on failure
//          ['ok' => true, 'filename' => '']    when no file was chosen (optional upload)
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
    $uploadDir = __DIR__ . "/../uploads/";
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
