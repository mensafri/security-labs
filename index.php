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
    <!-- Phase 1: Reconnaissance -->
    <div class="card" style="border-left: 4px solid #6366f1;">
        <h3>Phase 1: Reconnaissance</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Tahap awal pengumpulan informasi.</p>
        <div style="margin-top: 1rem;">
            <a href="modules/info_gathering/index.php" class="btn btn-sm">Info Gathering</a>
        </div>
    </div>

    <!-- Phase 2: Authentication -->
    <div class="card" style="border-left: 4px solid #ef4444;">
        <h3>Phase 2: Authentication</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Serangan pada mekanisme login.</p>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="modules/bruteforce/index.php" class="btn btn-sm">Bruteforce</a>
            <a href="modules/sqli/index.php" class="btn btn-sm">SQLi Login Bypass</a>
        </div>
    </div>

    <!-- Phase 3: Server-Side Attacks -->
    <div class="card" style="border-left: 4px solid #0f172a;">
        <h3>Phase 3: Server-Side</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Mengeksekusi perintah di server.</p>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="modules/cmd_injection/index.php" class="btn btn-sm">Cmd Injection</a>
            <a href="modules/file_inclusion/index.php" class="btn btn-sm">LFI</a>
            <a href="modules/file_upload/index.php" class="btn btn-sm">File Upload</a>
            <a href="ssrf.php" class="btn btn-sm">SSRF</a>
            <a href="modules/ssti/index.php" class="btn btn-sm">SSTI</a>
        </div>
    </div>

    <!-- Phase 4: Client-Side Attacks -->
    <div class="card" style="border-left: 4px solid #f59e0b;">
        <h3>Phase 4: Client-Side</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Serangan terhadap pengguna lain.</p>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="modules/xss/index.php" class="btn btn-sm">XSS</a>
            <a href="profile.php" class="btn btn-sm">CSRF</a>
        </div>
    </div>

    <!-- Phase 5: Access Control -->
    <div class="card" style="border-left: 4px solid #ec4899;">
        <h3>Phase 5: Access Control</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Pelanggaran hak akses.</p>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
            <a href="idor.php" class="btn btn-sm">IDOR</a>
            <a href="admin_panel.php" class="btn btn-sm">Privilege Escalation</a>
        </div>
    </div>

    <!-- Phase 6: Defense & Reporting -->
    <div class="card" style="border-left: 4px solid #10b981;">
        <h3>Phase 6: Reporting</h3>
        <p style="font-size: 0.9rem; color: var(--text-muted);">Defensive security & dokumentasi.</p>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 1rem;">
           <a href="modules/crypto/index.php" class="btn btn-sm">Cryptography</a>
           <a href="modules/reporting/index.php" class="btn btn-sm">Reporting Guide</a>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h3>⚠️ Peringatan</h3>
    <p>Web ini mengandung kerentanan keamanan yang disengaja. Jangan hosting di server publik!</p>
</div>

<?php include 'includes/footer.php'; ?>
