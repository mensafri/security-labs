<?php
session_start();
// Adjust path for deep nesting (modules/crypto/classic)
$base_path = '../../../';
include '../../../db.php';

// --- LOGIC CAESAR CIPHER START ---
function caesar_cipher($text, $shift, $action = 'encrypt') {
    $result = "";
    $shift = ($action == 'decrypt') ? (26 - $shift) : $shift;
    
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $base = ctype_upper($char) ? 65 : 97;
            $result .= chr((ord($char) - $base + $shift) % 26 + $base);
        } else {
            $result .= $char;
        }
    }
    return $result;
}
// --- LOGIC CAESAR CIPHER END ---

$result_text = "";
$input_text = "";
$shift_amount = 3;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $shift_amount = (int)$_POST['shift'];
    $mode = $_POST['mode']; // encrypt or decrypt
    
    $result_text = caesar_cipher($input_text, $shift_amount, $mode);
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
        <h1>🏛️ Caesar Cipher (Modul 1)</h1>
        <p>Teknik substitusi sederhana dengan menggeser huruf.</p>
    </div>

    <div class="grid-2">
        <!-- Form Area -->
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <label>Shift (Geseran)</label>
                <input type="number" name="shift" value="<?= $shift_amount ?>" min="1" max="25" style="width: 100px;">
                
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
                <p>Caesar Cipher menggeser setiap huruf alfabet sejumlah <strong>N</strong> posisi.</p>
                <p>Rumus (Enkripsi): <code>C = (P + Shift) mod 26</code></p>
                <p>Rumus (Dekripsi): <code>P = (C - Shift) mod 26</code></p>
            </div>

            <!-- CODE VIEWER -->
            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function caesar_cipher($text, $shift, $action = 'encrypt') {
    $result = "";
    // Jika decrypt, kita balik arah geseran
    // (Misal geser kanan 3, berarti decrypt geser kiri 3 atau kanan 23)
    $shift = ($action == 'decrypt') ? (26 - $shift) : $shift;
    
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            // Tentukan basis ASCII (Lower: 97, Upper: 65)
            $base = ctype_upper($char) ? 65 : 97;
            
            // Rumus: (Posisi Awal + Shift) MOD 26
            $ascii_offset = ord($char) - $base;
            $new_offset = ($ascii_offset + $shift) % 26;
            
            $result .= chr($base + $new_offset);
        } else {
            // Jangan ubah karakter non-huruf (spasi, angka, simbol)
            $result .= $char;
        }
    }
    return $result;
}
</pre>
                </div>
            </div>
            <!-- END CODE VIEWER -->
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
