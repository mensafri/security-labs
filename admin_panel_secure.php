<?php
include 'db.php';

// 1. Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. SECURE CHECK (Role Based Access Control)
// Cek apakah role-nya benar-benar 'admin'
if ($_SESSION['role'] !== 'admin') {
    http_response_code(403); // Berikan status Forbidden
    echo "<h1 style='color:red'>403 FORBIDDEN</h1>";
    echo "<p>Maaf <strong>" . $_SESSION['username'] . "</strong>, Anda bukan Admin.</p>";
    echo "<a href='profile.php'>Kembali ke jalan yang benar</a>";
    exit; // Stop eksekusi script!
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel Secure</title>
</head>

<body style="background-color: #ccffcc;">
    <h1 style="color: green;">✅ ADMIN PANEL (SECURE)</h1>
    <p>Selamat datang, Administrator <strong><?= $_SESSION['username'] ?></strong>.</p>
    <p>Hanya user dengan role 'admin' di database yang bisa melihat halaman ini.</p>
</body>

</html>