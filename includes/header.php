<?php
// includes/header.php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global Path (Prevent error if not set)
if (!isset($base_path)) {
    $base_path = './';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Labs Workshop</title>
    <link rel="stylesheet" href="<?= $base_path ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>

    <?php 
    // Only show sidebar if logged in
    // Note: We use include statement carefully to ensure it works from subdirectories
    if (isset($_SESSION['user_id'])) {
        include $base_path . 'includes/sidebar.php'; 
    }
    ?>

    <!-- Main Content Wrapper (Closed in footer.php) -->
    <main class="main-content">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <!-- Simple Header for Login Page -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <h1 style="color: var(--primary);">🛡️ Security Labs</h1>
                <p>Platform Pelatihan Keamanan Siber Interaktif</p>
            </div>
        <?php endif; ?>
