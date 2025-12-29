<?php
session_start();
$base_path = '../../';
include '../../db.php';

// Simulated Internal Login for Bruteforce
$msg = "";
$valid_pass = "admin123";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'];
    if ($pass === $valid_pass) {
        $msg = "<div class='alert alert-success'>✅ LOGIN BERHASIL! Password ditemukan.</div>";
    } else {
        $msg = "<div class='alert alert-error'>❌ Password Salah!</div>";
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #ef4444, #7f1d1d);">
        <h1>💥 Bruteforce Attack</h1>
        <p>Simulasi serangan menebak password secara paksa.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <p>Username target adalah: <strong>admin</strong>. Tugas Anda adalah mencari passwordnya.</p>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Gunakan tool seperti <strong>Burp Suite (Intruder)</strong> atau coba tebak manual password umum (misal: <code>admin</code>, <code>123456</code>, <code>password</code>, <code>admin123</code>).</li>
            <li>Perhatikan bahwa sistem ini <strong>TIDAK</strong> memiliki pembatasan percobaan login (Rate Limiting) atau CAPTCHA.</li>
        </ol>
    </div>

    <div style="max-width: 400px; margin: 0 auto; background: var(--background); padding: 2rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h3 style="text-align: center;">Portal Admin (Simulasi)</h3>
        <?= $msg ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" value="admin" readonly style="background: #e5e7eb;">
            <label>Password</label>
            <input type="password" name="password" placeholder="Tebak password..." required>
            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
