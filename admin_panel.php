<?php
include 'db.php';

// VULNERABLE CHECK
// Hanya mengecek "Apakah sudah login?", TAPI TIDAK mengecek "Apakah dia admin?"
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Halaman Admin</title>
</head>

<body style="background-color: #ffcccc;">
    <h1 style="color: red;">⚠️ SUPER SECRET ADMIN PANEL ⚠️</h1>
    <p>Selamat datang, <strong><?= $_SESSION['username'] ?></strong>.</p>

    <p>Karena Anda bisa mengakses halaman ini, Anda berhak memecat karyawan.</p>

    <button onclick="alert('Karyawan Dipecat!')">Pecat Semua Karyawan</button>
    <br><br>

    <div style="border:1px solid red; padding:10px; background:white;">
        <strong>Analisis Kerentanan:</strong><br>
        Halaman ini hanya mengecek <code>isset($_SESSION['user_id'])</code>.<br>
        Artinya, user biasa ('korban') pun bisa masuk ke sini jika tahu URL-nya.
    </div>
</body>

</html>