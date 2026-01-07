<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// --- LOGIC RC4 START ---
function rc4($key, $str) {
    // Key Scheduling Algorithm (KSA)
    $s = array();
    for ($i = 0; $i < 256; $i++) $s[$i] = $i;
    
    $j = 0;
    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
        $temp = $s[$i]; $s[$i] = $s[$j]; $s[$j] = $temp; // Swap
    }
    
    // Pseudo-Random Generation Algorithm (PRGA)
    $i = 0; $j = 0; $res = '';
    for ($y = 0; $y < strlen($str); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $temp = $s[$i]; $s[$i] = $s[$j]; $s[$j] = $temp; // Swap
        
        $res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]); // XOR
    }
    return $res;
}
// --- LOGIC RC4 END ---

$result_hex = "";
$input_text = "";
$key = "SECRET";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $key = $_POST['key'];
    // Output often binary, so we bin2hex for display
    $raw = rc4($key, $input_text);
    $result_hex = bin2hex($raw);
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #059669, #047857);">
        <h1>🌊 RC4 Stream Cipher (Modul 4)</h1>
        <p>Algoritma Stream Cipher yang cepat dan populer (sebelum WEP/TLS deprecation).</p>
    </div>

    <div class="grid-2">
        <div>
            <form method="POST">
                <label>Plaintext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <label>Key</label>
                <input type="text" name="key" value="<?= $key ?>" required style="width: 100%;">
                
                <button type="submit" class="btn" style="margin-top: 1rem;">⚡ Encrypt</button>
            </form>

            <?php if ($result_hex): ?>
                <div style="margin-top: 1.5rem; background: #ecfdf5; padding: 1rem; border: 1px solid #6ee7b7; border-radius: 4px;">
                    <strong>Hasil (Hex Format):</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_hex) ?>
                    </p>
                    <p style="font-size:0.8rem; color:gray;">*Stream cipher beroperasi pada level byte/bit, bukan huruf.</p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="alert alert-info">
                <h3>💡 Konsep Dasar</h3>
                <p>Stream Cipher meng-XOR setiap bit plaintext dengan bit dari <strong>Keystream</strong>.</p>
                <p>RC4 menghasilkan keystream pseudo-random berdasarkan kunci.</p>
                <code>C[i] = P[i] XOR K[i]</code>
            </div>

            <!-- CODE VIEWER -->
            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function rc4($key, $str) {
    // 1. KSA (Key Scheduling)
    // Inisialisasi array S[0..255]
    // Acak S berdasarkan Key
    
    // 2. PRGA (Generation)
    // Loop sepanjang plaintext
    // Generate byte keystream berikutnya dari S
    // Lakukan XOR dengan Plaintext
    
    $res .= $char ^ $keystream_byte;
    
    return $res;
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
