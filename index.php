<?php
session_start();
include 'db.php';
// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<div class="module-header">
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
    <p>Silakan pilih modul praktikum di bawah ini untuk memulai belajar.</p>
</div>

<div class="module-grid">
    <!-- Modul 1: Injection -->
    <div class="card">
        <h3>💉 Modul 1: Injection & Input</h3>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="modules/sqli/index.php" class="btn btn-sm">SQLi</a>
            <a href="modules/xss/index.php" class="btn btn-sm">XSS</a>
            <a href="ssrf.php" class="btn btn-sm">SSRF</a>
        </div>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Manipulasi input untuk menembus logika sistem.</p>
    </div>

    <!-- Modul 2: Access Control -->
    <div class="card">
        <h3>🔓 Modul 2: Access Control</h3>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="idor.php" class="btn btn-sm">IDOR</a>
            <a href="admin_panel.php" class="btn btn-sm">PrivEsc</a>
            <a href="profile.php" class="btn btn-sm">CSRF</a>
        </div>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Eksploitasi hak akses dan otorisasi.</p>
    </div>

    <!-- Modul 3: Data Protection -->
    <div class="card" style="border-color: var(--primary);">
        <h3>🔐 Modul 3: Data Protection</h3>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
           <a href="modules/crypto/modul1.php" class="btn btn-sm">AES</a>
           <a href="modules/crypto/modul2.php" class="btn btn-sm">Hashing</a>
        </div>
        <p style="margin-top: 0.5rem; font-size: 0.9rem;">Kriptografi, Enkripsi, dan Hashing.</p>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h3>⚠️ Peringatan</h3>
    <p>Web ini mengandung kerentanan keamanan yang disengaja. Jangan hosting di server publik!</p>
</div>

<?php include 'includes/footer.php'; ?>
