<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// DES Implementation using OpenSSL
function des_encrypt($text, $key) {
    // DES-ECB strictly for educational demo (insecure)
    // Format key to 8 chars
    $key = substr(str_pad($key, 8, '0'), 0, 8);
    return openssl_encrypt($text, 'DES-ECB', $key);
}

function des_decrypt($text, $key) {
    $key = substr(str_pad($key, 8, '0'), 0, 8);
    return openssl_decrypt($text, 'DES-ECB', $key);
}

$result_text = "";
$input_text = "";
$key = "DESKEY12";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $key = $_POST['key'];
    $mode = $_POST['mode'];
    
    if ($mode == 'encrypt') {
        $result_text = des_encrypt($input_text, $key);
    } else {
        $result_text = des_decrypt($input_text, $key);
    }
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #713f12, #451a03);">
        <h1>🛡️ DES Algorithm (Modul 6)</h1>
        <p>Data Encryption Standard. Algoritma Block Cipher legendaris (sekarang usang).</p>
    </div>

    <div class="grid-2">
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <label>Key (Max 8 Char)</label>
                <input type="text" name="key" value="<?= $key ?>" maxlength="8" required style="width: 100%;">
                
                <div style="margin-top: 1rem;">
                    <button type="submit" name="mode" value="encrypt" class="btn">🔒 Encrypt</button>
                    <button type="submit" name="mode" value="decrypt" class="btn btn-secondary">🔓 Decrypt</button>
                </div>
            </form>

            <?php if ($result_text): ?>
                <div style="margin-top: 1.5rem; background: #fefce8; padding: 1rem; border: 1px solid #fef08a; border-radius: 4px;">
                    <strong>Hasil:</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_text) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="alert alert-warning">
                <h3>⚠️ Legacy Algorithm</h3>
                <p>DES hanya menggunakan kunci 56-bit, yang sekarang sangat mudah di-bruteforce.</p>
                <p>Standar modern menggunakan <strong>AES</strong> (Advanced Encryption Standard).</p>
            </div>

            <!-- CODE VIEWER -->
            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode (PHP OpenSSL)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
// Menggunakan Library OpenSSL PHP
$cipher = openssl_encrypt(
    $plaintext, 
    'DES-ECB', // Algoritma & Mode
    $key       // Key 8 byte
);

// Hasil berupa Base64
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
