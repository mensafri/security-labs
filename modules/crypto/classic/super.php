<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// Super Cipher: Combination of Substitution (Vigenere) + Transposition
require_once 'vigenere.php'; // Reuse logic? No, let's copy to avoid function re-declare if included directly.
// Simpler: Just re-implement logic or ensure functions unique.
// We will implement Class or unique function names.

function super_encrypt($text, $vigenere_key, $trans_key) {
    // Step 1: Vigenere
    $subbed = vigenere_cipher_super($text, $vigenere_key, 'encrypt');
    // Step 2: Transposition
    $result = transposition_cipher_super($subbed, $trans_key, 'encrypt');
    return $result;
}

function super_decrypt($text, $vigenere_key, $trans_key) {
    // Reverse Order
    // Step 1: Reverse Transposition
    $untrans = transposition_cipher_super($text, $trans_key, 'decrypt');
    // Step 2: Reverse Vigenere
    $result = vigenere_cipher_super($untrans, $vigenere_key, 'decrypt');
    return $result;
}

// Helpers (Renamed to avoid collision with included files if expanding later)
function vigenere_cipher_super($text, $key, $action) {
    $result = "";
    $key = strtoupper($key);
    $key_idx = 0;
    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $base = ctype_upper($char) ? 65 : 97;
            $shift = ord($key[$key_idx % strlen($key)]) - 65;
            if ($action == 'decrypt') $shift = 26 - $shift;
            $result .= chr((ord($char) - $base + $shift) % 26 + $base);
            $key_idx++;
        } else $result .= $char;
    }
    return $result;
}

function transposition_cipher_super($text, $cols, $action) {
    // Simple Columnar logic
    $text = str_replace(' ', '_', $text);
    $len = strlen($text);
    $rows = ceil($len / $cols);
    $result = "";
    if ($action == 'encrypt') {
        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r < $rows; $r++) {
                $idx = $c + ($r * $cols);
                if ($idx < $len) $result .= $text[$idx];
            }
        }
    } else {
        // Dekripsi Transposisi Sederhana (Rectangular Assumption for simplicity)
        // ... (Logic same as transposition.php for edu demo)
        // To be safe for layout, let's use the 'encrypt' logic but filling Column-wise then Reading Row-wise
        $col_heights = array_fill(0, $cols, floor($len / $cols));
        for ($i=0; $i<$len%$cols; $i++) $col_heights[$i]++;
        $idx = 0; $grid = [];
        for ($c = 0; $c < $cols; $c++) {
             for ($r = 0; $r < $col_heights[$c]; $r++) $grid[$r][$c] = $text[$idx++];
        }
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) if (isset($grid[$r][$c])) $result .= $grid[$r][$c];
        }
    }
    return $result;
}

$result_text = "";
$input_text = "";
$key_vig = "SUPER";
$key_trans = 4;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $key_vig = $_POST['vigenere_key'];
    $key_trans = $_POST['trans_key'];
    $mode = $_POST['mode'];
    
    if ($mode == 'encrypt') {
        $result_text = super_encrypt($input_text, $key_vig, $key_trans);
    } else {
        $result_text = super_decrypt($input_text, $key_vig, $key_trans);
    }
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #be123c, #881337);">
        <h1>🦸 Super Cipher (Modul 3)</h1>
        <p>Gabungan dua teknik: <strong>Substitusi</strong> (Vigenere) + <strong>Transposisi</strong> (Columnar).</p>
    </div>

    <div class="grid-2">
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <div style="display: flex; gap: 1rem;">
                    <div style="flex:1">
                        <label>Vigenere Key</label>
                        <input type="text" name="vigenere_key" value="<?= $key_vig ?>" required>
                    </div>
                    <div style="flex:1">
                        <label>Transposisi Key (Col)</label>
                        <input type="number" name="trans_key" value="<?= $key_trans ?>" required>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <button type="submit" name="mode" value="encrypt" class="btn">🔒 Encrypt</button>
                    <button type="submit" name="mode" value="decrypt" class="btn btn-secondary">🔓 Decrypt</button>
                </div>
            </form>

            <?php if ($result_text): ?>
                <div style="margin-top: 1.5rem; background: #fff1f2; padding: 1rem; border: 1px solid #fecdd3; border-radius: 4px;">
                    <strong>Hasil Akhir:</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_text) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="alert alert-info">
                <h3>💡 Konsep Dasar</h3>
                <p>Super Cipher memperkuat keamanan dengan melakukan enkripsi berlapis.</p>
                <ol>
                    <li><strong>Langkah 1:</strong> Ubah huruf menggunakan Vigenere.</li>
                    <li><strong>Langkah 2:</strong> Acak posisi hasil tadi menggunakan Transposisi.</li>
                </ol>
                <p>Untuk dekripsi, lakukan urutan terbalik: Balikkan Transposisi dulu, baru Vigenere.</p>
            </div>

             <!-- CODE VIEWER -->
             <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function super_encrypt($text, $vig_key, $trans_key) {
    // 1. Substitusi
    $cipher1 = vigenere_cipher($text, $vig_key);
    
    // 2. Transposisi
    $cipher2 = transposition_cipher($cipher1, $trans_key);
    
    return $cipher2;
}

function super_decrypt($cipher, $vig_key, $trans_key) {
    // Urutan terbalik!
    // 1. Balikkan Transposisi
    $step1 = transposition_decrypt($cipher, $trans_key);
    
    // 2. Balikkan Vigenere
    $plain = vigenere_decrypt($step1, $vig_key);
    
    return $plain;
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
