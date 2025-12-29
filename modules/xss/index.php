<?php
session_start();
$base_path = '../../';
// XSS Lab
// Goals: Make the user input alert(1)
$input = isset($_GET['search']) ? $_GET['search'] : '';

// Validation for flag (Simulation)
// In real world, we'd need a bot to visit. Here, we just check if string contains the payload.
$success_msg = "";
if (strpos($input, '<script>alert(1)</script>') !== false || strpos($input, '<script>alert("1")</script>') !== false) {
    $success_msg = "Excellent! Payload script berhasil dieksekusi.";
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
        <h1>📢 Lab Cross-Site Scripting (XSS)</h1>
        <p>Suntikkan kode JavaScript berbahaya ke dalam halaman web.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Ketik script berikut di kolom pencarian: <br><code>&lt;script&gt;alert(1)&lt;/script&gt;</code></li>
            <li>Klik tombol <strong>Cari</strong>.</li>
            <li>Jika browser menampilkan Pop-up "1", maka halaman ini rentan terhadap Reflected XSS.</li>
            <li>Hal ini terjadi karena input pengguna langsung ditampilkan kembali tanpa sanitasi.</li>
        </ol>
    </div>

    <div style="max-width: 600px; margin: 0 auto; text-align: center;">
        <h3>🔎 Pencarian Produk</h3>
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari barang..." value="<?= $input // VULNERABLE: No htmlspecialchars! ?>">
            <button type="submit" class="btn">Cari</button>
        </form>

        <?php if ($input): ?>
            <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1rem;">
                <p>Hasil pencarian untuk: <strong><?= $input // VULNERABLE output ?></strong></p>
                <p class="text-muted">Tidak ditemukan.</p>
            </div>
        <?php endif; ?>

        <?php if ($success_msg): ?>
            <div class="alert alert-success" style="margin-top: 2rem; text-align: left;">
                <strong>✅ SERANGAN BERHASIL!</strong>
                <p>Browser mengeksekusi script yang anda masukkan. Dalam skenario nyata, serangan ini dapat mencuri Cookies (Session Hijacking) atau meredirect user ke situs phishing.</p>
                <div style="padding: 1rem; background: #fff; border: 1px dashed var(--success); margin-top: 1rem;">
                     <strong>Pesan Sistem:</strong> <?= $success_msg ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
