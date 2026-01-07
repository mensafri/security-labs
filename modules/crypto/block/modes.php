<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

// Visualization of ECB vs CBC
$input = "RAHASIARAHASIA"; // 16 bytes/chars for visual
$key = "KUNCIKEY"; // 8 bytes (DES-like block)

// Pseudo Logic for Educational Visualization
function pseudo_block_encrypt($text, $mode) {
    $blocks = str_split($text, 8); // 8-char blocks
    $output = [];
    $prev_cipher = "IV_VECTOR"; // IV for CBC
    
    foreach ($blocks as $i => $block) {
        // Pad if needed
        $block = str_pad($block, 8, "X");
        
        if ($mode == 'ECB') {
            // ECB: Encrypt block directly
            $encrypted = strtoupper(strrev($block)); // Dummy algo: Reverse + Upper
            $output[] = [
                'input' => $block,
                'process' => "Block $i -> Encrypt('$block')",
                'result' => $encrypted
            ];
        } else {
            // CBC: XOR with Prev Cipher first
            $xor_step = $block . "⊕" . substr($prev_cipher, 0, 3); // Visual XOR
            $encrypted = strtoupper(strrev($block)); // Dummy algo
            // In real CBC, result changes based on XOR. Here we simulate variation.
            if ($i > 0) $encrypted = str_shuffle($encrypted); 
            
            $output[] = [
                'input' => $block,
                'process' => "Block $i XOR " . ($i==0 ? "IV" : "Prev") . " -> Encrypt",
                'result' => $encrypted
            ];
            $prev_cipher = $encrypted;
        }
    }
    return $output;
}

$mode = isset($_POST['mode']) ? $_POST['mode'] : 'ECB';
$results = pseudo_block_encrypt($input, $mode);

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #b91c1c, #991b1b);">
        <h1>🧱 Block Cipher Modes (Modul 5)</h1>
        <p>Electronic Code Book (ECB) vs Cipher Block Chaining (CBC).</p>
    </div>

    <div class="grid-2">
        <div>
            <h3>Visualisasi Proses</h3>
            <form method="POST" style="margin-bottom: 1rem;">
                <label>Pilih Mode</label>
                <select name="mode" onchange="this.form.submit()" class="btn btn-secondary" style="width:100%">
                    <option value="ECB" <?= $mode == 'ECB' ? 'selected' : '' ?>>ECB (Electronic Code Book)</option>
                    <option value="CBC" <?= $mode == 'CBC' ? 'selected' : '' ?>>CBC (Cipher Block Chaining)</option>
                </select>
            </form>
            
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <?php foreach ($results as $idx => $res): ?>
                <div style="background: #fff; padding: 10px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: space-between;">
                    <span class="badge" style="background: #e5e7eb; color: #374151;"><?= $res['input'] ?></span>
                    <span>➡️ <?= $res['process'] ?> ➡️</span>
                    <span class="badge" style="background: #fee2e2; color: #b91c1c;"><?= $res['result'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <div class="alert alert-warning">
                <h3>⚠️ Kelemahan ECB</h3>
                <p>Dalam mode ECB, blok plaintext yang sama akan <strong>selalu</strong> menghasilkan ciphertext yang sama.</p>
                <p>Lihat contoh di kiri. Jika input "RAHASIA", hasil blok 1 dan 2 akan identik. Ini membocorkan pola data!</p>
            </div>
            
            <div class="alert alert-success">
                <h3>✅ Kelebihan CBC</h3>
                <p>CBC menggunakan <strong>IV (Initialization Vector)</strong> dan hasil blok sebelumnya.</p>
                <p>Meskipun input sama ("RAHASIA"), hasil enkripsinya akan berbeda setiap blok.</p>
            </div>
            
             <!-- CODE VIEWER -->
             <div style="margin-top: 1.5rem;">
                <button onclick="document.getElementById('code-view').style.display = document.getElementById('code-view').style.display === 'none' ? 'block' : 'none'" class="btn btn-sm btn-secondary" style="width: 100%;">
                    &lt;/&gt; Lihat Logika (PHP)
                </button>
                <div id="code-view" style="display: none; margin-top: 0.5rem; background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: 4px; overflow-x: auto; font-family: monospace; font-size: 0.9rem;">
<pre>
// ECB Mode
foreach ($blocks as $block) {
    // Enkripsi independen
    $cipher .= encrypt($block, $key);
}

// CBC Mode
$prev = $IV;
foreach ($blocks as $block) {
    // XOR dengan blok sebelumnya dulu
    $xor_block = $block ^ $prev;
    $curr_cipher = encrypt($xor_block, $key);
    
    $cipher .= $curr_cipher;
    $prev = $curr_cipher; // Simpan untuk ronde berikutnya
}
</pre>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
