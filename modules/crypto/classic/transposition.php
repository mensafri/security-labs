<?php
session_start();
// Adjust path for deep nesting (modules/crypto/classic)
$base_path = '../../../';
include '../../../db.php';

// --- LOGIC TRANSPOSITION START ---
// Simple Columnar Transposition
function transposition_cipher($text, $key, $action = 'encrypt') {
    // Basic logic: Write in rows, read in columns
    // NOTE: This is a simplified demo version where Key is just Number of Columns (int) for educational ease
    // Real columnar uses a keyword. Here we use "Rail Fence" variant or simple Block Grid.
    // Let's use simple Block Grid Transposition based on Column Count.
    // E.g. Text "HELLOWORLD", Cols 3 
    // H E L
    // L O W
    // O R L
    // D
    // Encrypt: HLOD EOR LWL
    
    // Ensure key is int
    if (!is_numeric($key)) $key = 4; 
    $cols = (int)$key;
    $text = str_replace(' ', '_', $text); // Replace space for visualarity
    $len = strlen($text);
    $rows = ceil($len / $cols);
    $result = "";

    if ($action == 'encrypt') {
        // Read columns
        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r < $rows; $r++) {
                $idx = $c + ($r * $cols);
                if ($idx < $len) {
                    $result .= $text[$idx];
                }
            }
        }
    } else {
        // Decrypt is tricky in columnar without padding knowledge.
        // Simplified: We assume rectangular block or careful filling.
        // For education: Let's use standard rectangular logic (rows * cols must >= len)
        // Reconstruct grid logic is needed.
        
        // Let's switch to RAIL FENCE (a simpler transposition) for the "Demo Code" effectively.
        // ... Actually user asked for Columnar/Transposition.
        // Let's simplify: Standard Columnar by Width
        // Reconstruct by writing column by column, then reading row by row.
        
        $col_heights = array_fill(0, $cols, floor($len / $cols));
        $remainder = $len % $cols;
        for ($i=0; $i<$remainder; $i++) $col_heights[$i]++;
        
        $idx = 0;
        $grid = [];
        for ($c = 0; $c < $cols; $c++) {
             for ($r = 0; $r < $col_heights[$c]; $r++) {
                 $grid[$r][$c] = $text[$idx++];
             }
        }
        
        // Read rows
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                if (isset($grid[$r][$c])) $result .= $grid[$r][$c];
            }
        }
    }
    return $result;
}
// --- LOGIC END ---

$result_text = "";
$input_text = "";
$key = 4;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_text = $_POST['text'];
    $key = $_POST['key'];
    $mode = $_POST['mode'];
    $result_text = transposition_cipher($input_text, $key, $mode);
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #8b5cf6, #5b21b6);">
        <h1>🔃 Transposition Cipher (Modul 2)</h1>
        <p>Teknik mengacak urutan huruf tanpa mengubah huruf itu sendiri (anagram).</p>
    </div>

    <div class="grid-2">
        <!-- Form Area -->
        <div>
            <form method="POST">
                <label>Plaintext / Ciphertext</label>
                <textarea name="text" rows="4" required placeholder="Ketik pesan..." style="width: 100%;"><?= htmlspecialchars($input_text) ?></textarea>
                
                <label>Jumlah Kolom (Konci Angka)</label>
                <input type="number" name="key" value="<?= $key ?>" min="2" max="10" required>
                
                <div style="margin-top: 1rem;">
                    <button type="submit" name="mode" value="encrypt" class="btn">🔒 Encrypt</button>
                    <button type="submit" name="mode" value="decrypt" class="btn btn-secondary">🔓 Decrypt</button>
                </div>
            </form>

            <?php if ($result_text): ?>
                <div style="margin-top: 1.5rem; background: #f3e8ff; padding: 1rem; border: 1px solid #d8b4fe; border-radius: 4px;">
                    <strong>Hasil:</strong>
                    <p style="font-family: monospace; font-size: 1.2rem; margin-top: 0.5rem; word-break: break-all;">
                        <?= htmlspecialchars($result_text) ?>
                    </p>
                    <small>*Spasi diganti dengan underscore (_) agar terlihat.</small>
                </div>
            <?php endif; ?>
        </div>

        <!-- Educational Side -->
        <div>
            <div class="alert alert-info">
                <h3>💡 Konsep Dasar</h3>
                <p>Teks ditulis ke dalam tabel secara horizontal (baris), lalu dibaca secara vertikal (kolom).</p>
                <p>Contoh: "HELLO", Kunci=2</p>
                <pre style="border:1px solid #ccc; padding:0.5rem;">H E L
L O</pre>
                <p>Hasil (Baca Turun): "HL EO L"</p>
            </div>

            <!-- CODE VIEWER -->
            <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Kode Program (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
function transposition_encrypt($text, $cols) {
    // 1. Tulis pesan dalam Grid (Row by Row)
    // 2. Baca pesan per Kolom
    
    $len = strlen($text);
    $rows = ceil($len / $cols);
    $result = "";

    for ($c = 0; $c < $cols; $c++) {
        for ($r = 0; $r < $rows; $r++) {
            // Hitung index 1D dari posisi Grid 2D
            $idx = $c + ($r * $cols);
            
            if ($idx < $len) {
                $result .= $text[$idx];
            }
        }
    }
    return $result;
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
