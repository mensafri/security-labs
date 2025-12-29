<?php
session_start();
// Fix nested include path issue
$base_path = '../../';
include '../../db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../includes/header.php';
?>

<div class="module-header">
    <h1>🔐 Modul Keamanan Data & Kriptografi</h1>
    <p>Pelajari fondasi keamanan aplikasi modern melalui teori dan studi kasus.</p>
</div>

<div class="card" style="margin-bottom: 2rem;">
    <h2>Daftar Materi</h2>
    <p>Silakan pelajari materi berikut secara berurutan:</p>
    
    <div style="display: grid; gap: 1rem; margin-top: 1.5rem;">
        <a href="modul1.php" class="card" style="margin:0; display: block; text-decoration: none; color: inherit; transition: transform 0.2s; border-left: 4px solid var(--primary);">
            <h3 style="color: var(--primary); margin-bottom: 0.5rem;">Modul 1: Kriptografi Dasar</h3>
            <p style="color: var(--text-muted); margin-bottom: 0;">Pengantar, Enkripsi Simetris (AES), Enkripsi Asimetris (RSA), dan Hybrid.</p>
        </a>
        
        <a href="modul2.php" class="card" style="margin:0; display: block; text-decoration: none; color: inherit; transition: transform 0.2s; border-left: 4px solid var(--success);">
            <h3 style="color: var(--success); margin-bottom: 0.5rem;">Modul 2: Hash & TLS</h3>
            <p style="color: var(--text-muted); margin-bottom: 0;">Fungsi Hash, Keamanan Password (Salt), Digital Signature, dan Protokol TLS/HTTPS.</p>
        </a>
    </div>
</div>

<div class="card">
    <h3>⬇️ Download Materi</h3>
    <p>Unduh modul lengkap untuk dibaca secara offline.</p>
    <a href="full_modul.html" target="_blank" class="btn btn-secondary">Download Modul (.html)</a>
</div>

<?php include '../../includes/footer.php'; ?>
