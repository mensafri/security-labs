<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// --- LOGIC AFFINE START ---
// Formula: E(x) = (ax + b) mod 26
// 'a' must be coprime to 26 (1, 3, 5, 7, 9, 11, 15, 17, 19, 21, 23, 25)

function gcd($a, $b) {
    return $b == 0 ? $a : gcd($b, $a % $b);
}

function modInverse($a, $m) {
    for ($x = 1; $x < $m; $x++) {
        if ((($a * $x) % $m) == 1) return $x;
    }
    return 1;
}

function affine_cipher($text, $a, $b, $action = 'encrypt') {
    if (gcd($a, 26) != 1) return "Error: Nilai 'a' harus relatif prima terhadap 26.";
    
    $result = "";
    $text = strtoupper($text);
    $a_inv = modInverse($a, 26);

    foreach (str_split($text) as $char) {
        if (ctype_alpha($char)) {
            $x = ord($char) - 65;
            
            if ($action == 'encrypt') {
                // E = (ax + b) mod 26
                $val = ($a * $x + $b) % 26;
            } else {
                // D = a^-1 (y - b) mod 26
                // Handle negative mod in PHP
                $val = ($a_inv * ($x - $b)) % 26;
                if ($val < 0) $val += 26;
            }
            
            $result .= chr($val + 65);
        } else {
            $result .= $char;
        }
    }
    return $result;
}
// --- LOGIC END ---

$result_text = "";
$input_text = "";
$val_a = 5;
$val_b = 8;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $val_a = (int)$_POST['a'];
    $val_b = (int)$_POST['b'];
    $mode = $_POST['mode'];
    $result_text = affine_cipher($input_text, $val_a, $val_b, $mode);
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #be185d, #9d174d);">
        <h1>📐 Affine Cipher (Modul 3)</h1>
        <p>Kombinasi aritmatika modular dengan perkalian dan penjumlahan.</p>
    </div>

    <div class="grid-2">
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <div style="display: flex; gap: 1rem;">
                    <div>
                        <label>Nilai a (Coprime 26)</label>
                        <input type="number" name="a" value="<?= $val_a ?>" required>
                        <small>Ganjil, bukan 13/26.</small>
                    </div>
                    <div>
                        <label>Nilai b (Shift)</label>
                        <input type="number" name="b" value="<?= $val_b ?>" required>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <button type="submit" name="mode" value="encrypt" class="btn">🔒 Encrypt</button>
                    <button type="submit" name="mode" value="decrypt" class="btn btn-secondary">🔓 Decrypt</button>
                </div>
            </form>

            <?php if ($result_text): ?>
                <div style="margin-top: 1.5rem; background: #fce7f3; padding: 1rem; border: 1px solid #fbcfe8; border-radius: 4px;">
                    <strong>Hasil:</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_text) ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="alert alert-info">
                <h3>💡 Konsep Dasar</h3>
                <p>Rumus Enkripsi: <code>E(x) = (a*x + b) mod 26</code></p>
                <p>Rumus Dekripsi: <code>D(y) = a⁻¹(y - b) mod 26</code></p>
                <p>Syarat: <strong>a</strong> harus relatif prima dengan 26 agar memiliki invers modular.</p>
            </div>

            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function affine_cipher($text, $a, $b) {
    // Cari invers modular dari a terhadap 26
    $a_inv = modInverse($a, 26);
    
    foreach (chars as x) {
        // Enkripsi
        $y = ($a * $x + $b) % 26;
        
        // Dekripsi
        // Kita butuh invers karena pembagian tidak ada di mod
        $plain = ($a_inv * ($y - $b)) % 26;
    }
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
