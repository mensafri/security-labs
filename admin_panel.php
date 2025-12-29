<?php
session_start();
$base_path = './';
include 'db.php';

// VULNERABILITY: Only checks if logged in, NOT if role is admin
// This is the core of the Privilege Escalation lab.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'includes/header.php';
?>

<div class="card" style="border: 2px solid var(--danger);">
    <h1 style="color: var(--danger); text-align: center;">💀 ADMIN PANEL (Unsecured)</h1>
    <p style="text-align: center;">Halaman ini seharusnya hanya untuk Admin. Jika Anda User biasa dan bisa melihat ini, berarti sistem memiliki celah <em>Privilege Escalation</em>.</p>
    
    <div style="margin: 2rem 0; padding: 1rem; background: #fffbe6; border-radius: var(--radius); text-align: center; border: 1px solid var(--warning);">
        <h3 style="color: #d97706; margin-top:0;">⚠️ Evaluasi Keamanan</h3>
        <p>Aplikasi hanya mengecek <code>isset($_SESSION['user_id'])</code> tanpa memverifikasi <code>$_SESSION['role'] === 'admin'</code>.</p>
        <p>User Role Anda saat ini: <strong><?= htmlspecialchars($_SESSION['role'] ?? 'user') ?></strong></p>
    </div>

    <div style="display: grid; gap: 1rem; max-width: 400px; margin: 0 auto;">
        <button class="btn btn-secondary" onclick="alert('Action Simulated: Database Deleted')">🗑️ Hapus Database</button>
        <button class="btn btn-secondary" onclick="alert('Action Simulated: Salary Increased')">💰 Naikkan Gaji Sendiri</button>
        <button class="btn btn-secondary" onclick="alert('Action Simulated: Staff Fired')">👋 Pecat Karyawan</button>
    </div>
</div>

<?php include 'includes/footer.php'; ?>