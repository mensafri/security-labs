<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// --- LOGIC VIGENERE CIPHER START ---
function vigenere_cipher($text, $key, $action = 'encrypt') {
    $result = "";
    $key = strtoupper($key);
    $key_len = strlen($key);
    $key_idx = 0;
    
    if ($key_len == 0) return $text;

    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $base = ctype_upper($char) ? 65 : 97;
            $shift = ord($key[$key_idx % $key_len]) - 65;
            
            if ($action == 'decrypt') {
                $shift = 26 - $shift; // Invert shift
            }

            $current_pos = ord($char) - $base;
            $new_pos = ($current_pos + $shift) % 26;
            $result .= chr($base + $new_pos);
            
            $key_idx++; // Move to next letter in key
        } else {
            $result .= $char;
        }
    }
    return $result;
}
// --- LOGIC VIGENERE CIPHER END ---

$result_text = "";
$input_text = "";
$key = "KUNCI";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $key = preg_replace("/[^A-Za-z]/", "", $_POST['key']); // Key must be alpha
    $mode = $_POST['mode'];
    
    $result_text = vigenere_cipher($input_text, $key, $mode);
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
        <h1>🗝️ Vigenere Cipher (Modul 1)</h1>
        <p>Pengembangan dari Caesar, menggunakan <strong>Kunci Kata</strong> (Polyalphabetic).</p>
    </div>

    <div class="grid-2">
        <!-- Form Area -->
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <label>Secret Key (Huruf Saja)</label>
                <input type="text" name="key" value="<?= htmlspecialchars($key) ?>" required style="width: 100%; text-transform: uppercase;">
                
                <div style="margin-top: 1rem;">
                    <button type="submit" name="mode" value="encrypt" class="btn">🔒 Encrypt</button>
                    <button type="submit" name="mode" value="decrypt" class="btn btn-secondary">🔓 Decrypt</button>
                </div>
            </form>

            <?php if ($result_text): ?>
                <div style="margin-top: 1.5rem; background: #e0f2fe; padding: 1rem; border: 1px solid #bae6fd; border-radius: 4px;">
                    <strong>Hasil:</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_text) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Educational Side -->
        <div>
            <div class="alert alert-info">
                <h3>💡 Konsep Dasar</h3>
                <p>Vigenere menggunakan kunci yang diulang-ulang untuk menggeser huruf.</p>
                <p>Jika Kunci = "ABC", maka:</p>
                <ul>
                    <li>Huruf ke-1 digeser 0 (A)</li>
                    <li>Huruf ke-2 digeser 1 (B)</li>
                    <li>Huruf ke-3 digeser 2 (C)</li>
                </ul>
            </div>

            <!-- CODE VIEWER -->
            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function vigenere_cipher($text, $key, $action) {
    // Siapkan key (hapus spasi, uppercase)
    $key = strtoupper($key);
    $key_idx = 0;
    
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            // Hitung shift berdasarkan huruf kunci saat ini
            // Ord('A') = 65, jadi Ord(KeyChar) - 65 = Nilai Shift 0-25
            $current_key_char = $key[$key_idx % strlen($key)];
            $shift = ord($current_key_char) - 65;
            
            // Jika decrypt, balik arah shift
            if ($action == 'decrypt') $shift = 26 - $shift;

            // ... Lakukan pergeseran seperti Caesar ...
            
            // Pindah ke huruf kunci berikutnya
            $key_idx++;
        }
        // ...
    }
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
