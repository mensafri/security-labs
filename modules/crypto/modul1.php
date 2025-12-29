<?php
session_start();
$base_path = '../../';
include '../../db.php';
// Helper function for AES
function simple_encrypt($text, $key) {
    if (empty($text) || empty($key)) return "";
    $method = "AES-128-ECB"; // Simple mode for demo (Not recommended for prod)
    return openssl_encrypt($text, $method, $key);
}

function simple_decrypt($text, $key) {
    if (empty($text) || empty($key)) return "";
    $method = "AES-128-ECB";
    return openssl_decrypt($text, $method, $key);
}

// Handle Form Submission
$result_encrypt = "";
$result_decrypt = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['encrypt'])) {
        $result_encrypt = simple_encrypt($_POST['plain_text'], $_POST['key_enc']);
    }
    if (isset($_POST['decrypt'])) {
        $result_decrypt = simple_decrypt($_POST['cipher_text'], $_POST['key_dec']);
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../includes/header.php';
?>

<div class="card">
    <a href="index.php" class="btn btn-secondary" style="margin-bottom: 1rem;">← Kembali ke Menu</a>
    
    <div class="module-header" style="background: linear-gradient(135deg, #4f46e5, #818cf8);">
        <h1>🛠️ Lab Kriptografi Simetris (AES)</h1>
        <p>Praktik langsung enkripsi dan dekripsi pesan menggunakan algoritma AES-128.</p>
    </div>

    <!-- PANDUAN -->
    <div class="alert alert-info">
        <strong>📝 Misi Praktikum:</strong>
        <ol style="margin-left: 1.5rem;">
            <li>Masukkan pesan rahasia di kolom <strong>Plaintext</strong>.</li>
            <li>Buat kunci rahasia (password) di kolom <strong>Kunci</strong>.</li>
            <li>Klik <strong>Enkripsi</strong> untuk melihat hasil acak (Ciphertext).</li>
            <li>Copy Ciphertext tersebut ke form bawah (Dekripsi) untuk mengembalikannya menjadi pesan asli.</li>
        </ol>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
        
        <!-- FORM ENKRIPSI -->
        <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
            <h3 style="color: var(--primary);">🔒 Simulasi Enkripsi</h3>
            <form method="POST">
                <label>Pesan Asli (Plaintext)</label>
                <input type="text" name="plain_text" placeholder="Contoh: Serangan fajar jam 10" required 
                       value="<?= isset($_POST['plain_text']) ? htmlspecialchars($_POST['plain_text']) : '' ?>">

                <label>Kunci Rahasia</label>
                <input type="text" name="key_enc" placeholder="password123" required
                       value="<?= isset($_POST['key_enc']) ? htmlspecialchars($_POST['key_enc']) : '' ?>">

                <button type="submit" name="encrypt" class="btn" style="width: 100%;">Enkripsi Pesan</button>
            </form>

            <?php if ($result_encrypt): ?>
                <div style="margin-top: 1rem;">
                    <label>Hasil Ciphertext:</label>
                    <textarea readonly style="width: 100%; padding: 0.5rem; background: #e0e7ff; border: 1px solid #c7d2fe; border-radius: var(--radius); color: #3730a3; font-family: monospace; height: 60px;"><?= $result_encrypt ?></textarea>
                    <small style="color: var(--text-muted);">Copy teks ini untuk didekripsi.</small>
                </div>
            <?php endif; ?>
        </div>

        <!-- FORM DEKRIPSI -->
        <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
            <h3 style="color: var(--success);">🔓 Simulasi Dekripsi</h3>
            <form method="POST">
                <label>Pesan Terenkripsi (Ciphertext)</label>
                <input type="text" name="cipher_text" placeholder="Paste kode aneh di sini..." required
                       value="<?= isset($_POST['cipher_text']) ? htmlspecialchars($_POST['cipher_text']) : '' ?>">

                <label>Kunci Rahasia</label>
                <input type="text" name="key_dec" placeholder="Kunci harus sama!" required
                       value="<?= isset($_POST['key_dec']) ? htmlspecialchars($_POST['key_dec']) : '' ?>">

                <button type="submit" name="decrypt" class="btn btn-secondary" style="width: 100%; background: var(--success);">Dekripsi Pesan</button>
            </form>

            <?php if ($result_decrypt): ?>
                <div style="margin-top: 1rem;">
                    <label>Pesan Asli:</label>
                    <div style="padding: 1rem; background: #dcfce7; border: 1px solid #86efac; color: #166534; border-radius: var(--radius); font-weight: bold;">
                        <?= htmlspecialchars($result_decrypt) ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1rem;">
        <h3>🧠 Analisis Keamanan</h3>
        <p>Cobalah melakukan dekripsi dengan <strong>Kunci yang Salah</strong>. Apa yang terjadi?</p>
        <p>Dalam algoritma simetris, jika kunci dekripsi beda 1 karakter saja, maka pesan asli <strong>tidak akan bisa kembali</strong> (keluar sampah atau gagal). Ini menunjukkan prinsip <em>Confidentiality</em> yang kuat selama kunci terjaga.</p>
    </div>

    <div style="margin-top: 2rem; background: #f0fdf4; padding: 1.5rem; border-left: 4px solid var(--success); border-radius: var(--radius);">
        <h3 style="color: var(--success); margin-top:0;">✅ Kesimpulan Praktikum</h3>
        <p>Anda telah mempraktikkan bagaimana data diubah menjadi bentuk yang tidak terbaca (Ciphertext) dan dikembalikan lagi (Plaintext). Dalam aplikasi nyata, proses ini terjadi di background saat Anda mengakses website HTTPS.</p>
    </div>

    <div style="margin-top: 2rem; text-align: right;">
        <a href="modul2.php" class="btn">Lanjut ke Lab Hashing →</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
