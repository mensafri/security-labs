<?php
session_start();
$base_path = '../../';
include '../../db.php';

$products = [];
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // VULNERABLE: Direct concatenation
    $query = "SELECT * FROM notes WHERE id = " . $id; 
    
    // Using mysqli_multi_query to simulate blind/stacked? No, UNION is better.
    // Simple fetch
    try {
        $result = mysqli_query($conn, $query);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
        <h1>💉 SQL Injection: UNION Attack</h1>
        <p>Teknik menggabungkan hasil query untuk mencuri data dari tabel lain.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <p>Halaman ini menampilkan detail Catatan berdasarkan ID. (Tabel: <code>notes</code>, Kolom: <code>id, title, content</code>)</p>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Cek jumlah kolom: <code>1 ORDER BY 3-- -</code> (Jika error berarti kolom > 3 atau < 3).</li>
            <li>Cek posisi output: <code>1 UNION SELECT 1, 2, 3-- -</code>. Lihat angka mana yang muncul.</li>
            <li><strong>Dump Data User:</strong> Ganti angka yang muncul dengan query rahasia.</li>
            <li>Payload: <code>0 UNION SELECT 1, username, password FROM users-- -</code></li>
        </ol>
    </div>

    <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h3>🔎 Note Viewer</h3>
        <form method="GET" style="display:flex; gap:1rem;">
            <input type="text" name="id" placeholder="Masukkan ID Note (1, 2, ...)" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '' ?>">
            <button type="submit" class="btn">Lihat</button>
        </form>

        <?php if (isset($result) && $result): ?>
            <div style="margin-top: 1rem; padding: 1rem; border: 1px solid var(--primary);">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <h2><?= $row['title'] ?? 'No Title' ?></h2> <!-- Potentially '2' or 'username' -->
                    <p><?= $row['content'] ?? 'No Content' ?></p> <!-- Potentially '3' or 'password' -->
                <?php endwhile; ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-error">Database Error: <?= $error ?></div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
