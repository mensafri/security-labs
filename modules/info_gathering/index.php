<?php
session_start();
$base_path = '../../';
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
        <h1>🔍 Information Gathering</h1>
        <p>Tahap pertama dalam Penetration Testing: Mengumpulkan informasi tentang target.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Cek `robots.txt`:</strong> Tambahkan <code>/robots.txt</code> di akhir URL utama (misal: buat file robots.txt di root folder dulu jika belum ada).</li>
            <li><strong>Inspect Element:</strong> Klik kanan -> Inspect (atau Ctrl+Shift+I). Lihat kode sumber HTML halaman ini. Apakah ada komentar developer yang tertinggal?</li>
        </ol>
    </div>

    <!-- Hidden Comment for Educational Purpose -->
    <!-- TODO: Hapus kredensial ini sebelum production! User: dev_test / Pass: dev123 -->

    <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h3>🌐 Target Area</h3>
        <p>Halaman ini terlihat biasa saja. Namun seorang hacker akan selalu memeriksa "bawah kap mesin" (Source Code).</p>
        <p>Cobalah temukan kredensial rahasia yang tersembunyi di source code halaman ini!</p>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
