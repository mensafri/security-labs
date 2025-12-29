<?php
session_start();
$base_path = '../../';
include '../../db.php';
// Create a dummy text file for inclusion demo
file_put_contents("catatan_rahasia.txt", "INI ADALAH FILE RAHASIA LOKAL DI SERVER!\nJangan sampai terbaca user.");

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$content = "";

// VULNERABLE LFI
// Code directly includes whatever is in $_GET['page']
// We simply capture output buffer to show it nicely
ob_start();
if ($page == 'home') {
    echo "<h3>Selamat Datang di Halaman Reader</h3><p>Pilih file untuk dibaca.</p>";
} else {
    // Suppress warning for demo cleaner output, but in real lab errors help debugging
    @include($page); 
}
$content = ob_get_clean();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #d946ef, #a21caf);">
        <h1>📁 File Inclusion (LFI)</h1>
        <p>Local File Inclusion memungkinkan penyerang membaca file internal server.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Klik tombol menu di bawah untuk melihat pola URL (<code>?page=...</code>).</li>
            <li>Coba panggil file rahasia yang ada di folder ini: <code>catatan_rahasia.txt</code>.</li>
            <li>Payload URL: <code>index.php?page=catatan_rahasia.txt</code></li>
            <li>(Advanced) Coba Path Traversal (keluar folder) jika file sistem Windows bisa dibaca, misal: <code>../../../../Windows/win.ini</code>.</li>
        </ol>
    </div>

    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
        <a href="?page=home" class="btn btn-secondary">Home</a>
        <a href="?page=about.txt" class="btn btn-secondary">About (Dummy)</a>
        <!-- We create about.txt on the fly just in case -->
        <?php file_put_contents("about.txt", "<h3>About Us</h3><p>Kami adalah perusahaan fiktif untuk demo LFI.</p>"); ?>
    </div>

    <div style="background: #fff; padding: 2rem; border: 1px solid #ccc; min-height: 200px; border-radius: var(--radius);">
        <?= $content ?>
        <!-- If include failed, show message -->
        <?php if (empty($content) && $page != 'home') echo "<p style='color:red'>File tidak ditemukan atau kosong.</p>"; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
