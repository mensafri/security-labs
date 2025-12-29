<?php
session_start();
$base_path = './';
include 'db.php';
// Cek Login (Optional, but good practice for labs)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<style>
    .terminal {
        background: #1e1e1e;
        color: #00ff00;
        padding: 1rem;
        border-radius: var(--radius);
        font-family: 'Courier New', monospace;
        overflow-x: auto;
        margin-top: 1.5rem;
        border: 1px solid #333;
    }
    .log-entry {
        margin-bottom: 0.5rem;
        border-bottom: 1px dashed #444;
        padding-bottom: 0.5rem;
    }
    .log-entry:last-child { border-bottom: none; }
    .label { color: #00ffff; font-weight: bold; }
    .error { color: #ef4444; }
    .warning { color: #f59e0b; }
</style>

<div class="card">
    <h1>🌐 Server Utility (Hybrid)</h1>
    <p>Tools ini menggunakan <strong>cURL</strong> untuk HTTP dan <strong>PHP Streams</strong> untuk protokol lokal.</p>

    <div class="alert alert-info" style="margin-top: 1rem;">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li><strong>Cek Port Internal:</strong> Masukkan <code>http://localhost:3306</code>. Jika loading lama/muncul error MySQL, berarti port terbuka.</li>
            <li><strong>Baca File Lokal (Windows):</strong> Gunakan <code>php://filter/read=string/resource=c:/windows/win.ini</code></li>
            <li>Ini membuktikan server bisa diperintah untuk mengakses resource internal yang harusnya tertutup dari publik.</li>
        </ol>
    </div>

    <form method="GET" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
        <input type="text" name="url" placeholder="Contoh: http://google.com atau php://filter/..." value="<?= isset($_GET['url']) ? htmlspecialchars($_GET['url']) : '' ?>" style="margin-bottom: 0;">
        <button type="submit" class="btn">Execute Request</button>
    </form>

    <?php
    if (isset($_GET['url']) && !empty($_GET['url'])) {
        $url = $_GET['url'];

        echo "<div class='terminal'>";
        echo "<div class='log-entry'>[INFO] Menerima perintah: <span style='color:white'>$url</span></div>";

        // LOGIKA HYBRID
        // Cek apakah protokolnya HTTP/HTTPS?
        if (preg_match('/^https?:\/\//i', $url)) {
            // --- MODE 1: cURL (Bagus untuk Port Scanning & Web) ---
            echo "<div class='log-entry'>[MODE] Terdeteksi protokol HTTP/S -> Menggunakan engine <strong>cURL</strong></div>";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 3);
            curl_setopt($ch, CURLOPT_HEADER, true); // Tampilkan Header
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Removed unsafe default unless necessary

            $response = curl_exec($ch);
            $error_msg = curl_error($ch);

            if ($error_msg) {
                echo "<div class='log-entry error'>[FAIL] cURL Error: $error_msg</div>";
            } else {
                echo "<div class='log-entry'>[SUCCESS] HTTP Request Berhasil.</div>";
                echo "<pre style='color:white; white-space: pre-wrap;'>" . htmlspecialchars(substr($response, 0, 800)) . "...</pre>";
            }
            curl_close($ch);
        } else {
            // --- MODE 2: PHP Streams (Bagus untuk LFI & Source Code Leak) ---
            echo "<div class='log-entry warning'>[MODE] Protokol Non-HTTP terdeteksi -> Menggunakan engine <strong>file_get_contents()</strong></div>";
            echo "<div class='log-entry'>[INFO] Mencoba membaca resource stream...</div>";

            // Suppress error (@) agar tidak bocor path asli server di browser
            $content = @file_get_contents($url);

            if ($content !== false) {
                echo "<div class='log-entry'>[SUCCESS] Stream berhasil dibaca!</div>";
                echo "<div class='log-entry'>[DATA PREVIEW]:</div>";
                echo "<textarea style='width:100%; height:150px; background:#333; color:white; border:none; padding:10px; font-family: monospace;'>" . htmlspecialchars($content) . "</textarea>";
            } else {
                echo "<div class='log-entry error'>[FAIL] Gagal membaca resource. Pastikan file ada atau protokol didukung.</div>";
                // Tampilkan error terakhir untuk debug mahasiswa
                $error = error_get_last();
                if ($error) {
                    echo "<div class='log-entry error'>[DEBUG] System Error: " . $error['message'] . "</div>";
                }
            }
        }
        echo "</div>";
    }
    ?>
</div>

<div class="alert alert-info">
    <strong>🎯 Panduan Payload:</strong>
    <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
        <li><small>cURL Mode:</small> <code>http://localhost:3306</code> (Cek Port MySQL)</li>
        <li><small>cURL Mode:</small> <code>http://security-labs.test/admin_internal.php</code> (Bypass Admin)</li>
        <li><small>Stream Mode:</small> <code>file:///C:/Windows/win.ini</code> (Baca File Windows)</li>
        <li><small>Stream Mode:</small> <code>php://filter/read=convert.base64-encode/resource=db.php</code> (Baca Source Code)</li>
    </ul>
</div>

<?php include 'includes/footer.php'; ?>