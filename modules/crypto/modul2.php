<?php
session_start();
$base_path = '../../';
include '../../db.php';
// Handle Hashing Logic
$input_text = isset($_POST['input_text']) ? $_POST['input_text'] : '';
$use_salt = isset($_POST['use_salt']);

$md5_res = $input_text ? md5($input_text) : '';
$sha1_res = $input_text ? sha1($input_text) : '';
$sha256_res = $input_text ? hash('sha256', $input_text) : '';

// SALT LOGIC
$pass_weak = "rahasia123";
$salt_demo = "Xy9@z#";
$hash_weak = md5($pass_weak); 
$hash_strong = hash('sha256', $pass_weak . $salt_demo);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../includes/header.php';
?>

<div class="card">
    <a href="modul1.php" class="btn btn-secondary" style="margin-bottom: 1rem;">← Kembali ke Lab AES</a>

    <div class="module-header" style="background: linear-gradient(135deg, #059669, #10b981);">
        <h1>🔬 Lab Hashing & Keamanan Password</h1>
        <p>Eksperimen Fungsi Hash satu arah dan melihat efek "Salt" pada keamanan password.</p>
    </div>

    <!-- BAGIAN 1: HASH GENERATOR -->
    <div style="margin-bottom: 3rem;">
        <h3>1. Hash Generator</h3>
        <p class="text-muted">Ketik apa saja untuk melihat bagaimana teks berubah menjadi kode acak (Digest).</p>
        
        <form method="POST" style="background: var(--background); padding: 1.5rem; border-radius: var(--radius);">
            <label>Input Teks</label>
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="input_text" placeholder="Ketik halo dunia..." value="<?= htmlspecialchars($input_text) ?>" style="margin-bottom: 0;" autocomplete="off">
                <button type="submit" class="btn">Generate Hash</button>
            </div>
        </form>

        <?php if ($input_text): ?>
            <div style="margin-top: 1.5rem; display: grid; gap: 1rem;">
                <!-- MD5 -->
                <div style="border: 1px solid var(--danger); padding: 1rem; border-radius: var(--radius); background: #fef2f2;">
                    <div style="font-weight: bold; color: var(--danger); margin-bottom: 0.5rem;">MD5 (Tidak Aman - Pendek)</div>
                    <code style="word-break: break-all; color: var(--danger);"><?= $md5_res ?></code>
                </div>
                <!-- SHA1 -->
                <div style="border: 1px solid var(--warning); padding: 1rem; border-radius: var(--radius); background: #fffbeb;">
                    <div style="font-weight: bold; color: var(--warning); margin-bottom: 0.5rem;">SHA-1 (Tidak Direkomendasikan)</div>
                    <code style="word-break: break-all; color: var(--warning);"><?= $sha1_res ?></code>
                </div>
                <!-- SHA256 -->
                <div style="border: 1px solid var(--success); padding: 1rem; border-radius: var(--radius); background: #f0fdf4;">
                    <div style="font-weight: bold; color: var(--success); margin-bottom: 0.5rem;">SHA-256 (Standar Aman)</div>
                    <code style="word-break: break-all; color: var(--success);"><?= $sha256_res ?></code>
                </div>
            </div>
            
            <div class="alert alert-info" style="margin-top: 1.5rem;">
                <strong>💡 Percobaan Avalanche Effect:</strong>
                <p>Coba ubah satu huruf saja pada input (misal huruf besar ke kecil). Perhatikan bahwa seluruh kode hash berubah total! Ini membuktikan bahwa hash sangat sensitif terhadap perubahan, menjamin <em>Integritas Data</em>.</p>
            </div>
        <?php endif; ?>
    </div>

    <hr style="border: 0; border-top: 1px solid var(--border); margin: 3rem 0;">

    <!-- BAGIAN 2: SALT DEMO -->
    <div>
        <h3>2. Studi Kasus: Mengapa Password butuh 'Salt'?</h3>
        <p>Bayangkan password User adalah: <code>rahasia123</code></p>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 1.5rem;">
            
            <!-- UNSAFE -->
            <div class="card" style="border: 1px solid var(--danger);">
                <h4 style="color: var(--danger);">❌ Penyimpanan Tidak Aman (Plain Hash)</h4>
                <p>Hanya di-hash MD5 biasa.</p>
                
                <div style="background: #333; color: #fff; padding: 10px; font-family: monospace; font-size: 0.9em;">
                    Input: "rahasia123"<br>
                    Hash: <?= $hash_weak ?>
                </div>

                <div class="alert alert-error" style="margin-top: 1rem; margin-bottom: 0;">
                    <strong>Bahaya:</strong>
                    <p>Hacker punya "Kamus Hash" (Rainbow Table). Mereka bisa mencari kode hash di atas di Google, dan langsung ketemu password aslinya.</p>
                </div>
            </div>

            <!-- SAFE -->
            <div class="card" style="border: 1px solid var(--success);">
                <h4 style="color: var(--success);">✅ Penyimpanan Aman (Hash + Salt)</h4>
                <p>Ditambah string acak (Salt) sebelum di-hash.</p>

                <div style="background: #333; color: #fff; padding: 10px; font-family: monospace; font-size: 0.9em;">
                    Input: "rahasia123" + "<?= $salt_demo ?>"<br>
                    Hash: <?= substr($hash_strong, 0, 20) ?>... (Sangat Panjang)
                </div>

                <div class="alert alert-info" style="margin-top: 1rem; margin-bottom: 0;">
                    <strong>Keunggulan:</strong>
                    <p>Karena ada tambahan karakter aneh, hash ini tidak ada di kamus manapun. Password aman dari pencarian Rainbow Table!</p>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; text-align: center;">
        <a href="index.php" class="btn btn-secondary">← Kembali ke Daftar Modul</a>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
