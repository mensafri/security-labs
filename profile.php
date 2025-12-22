<?php
// profile.php
include 'db.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// --- LOGIKA GANTI EMAIL (TARGET CSRF) ---
if (isset($_POST['ganti_email'])) {
    $new_email = $_POST['email'];
    $id = $_SESSION['user_id'];

    // VULNERABLE: Tidak ada token CSRF
    mysqli_query($conn, "UPDATE users SET email='$new_email' WHERE id=$id");
    $msg = "Email berhasil diubah menjadi: $new_email";
}

// Ambil data user terbaru
$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard User</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 20px auto;
            line-height: 1.6;
        }

        .nav-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #90caf9;
            margin-bottom: 20px;
        }

        .nav-box a {
            text-decoration: none;
            color: #1565c0;
            font-weight: bold;
            margin-right: 15px;
        }

        .nav-box a:hover {
            text-decoration: underline;
        }

        .role-badge {
            background: #333;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.8em;
        }

        .form-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <h1>Halo, <?= $user['username']; ?> <span class="role-badge"><?= $user['role']; ?></span></h1>

    <div class="nav-box">
        <p><strong>📂 Menu Praktikum:</strong></p>
        <a href="ssrf.php">1. Lab SSRF</a>
        <a href="idor.php">2. Lab IDOR (BAC)</a>
        <a href="admin_panel.php">3. Lab Admin Panel (BAC)</a>
        <a href="logout.php" style="color:red; float:right;">Keluar (Logout)</a>
    </div>

    <div class="form-box">
        <h3>👤 Profil Saya</h3>
        <p>Email saat ini: <strong><?= $user['email']; ?></strong></p>

        <?php if (isset($msg)) echo "<p class='success'>$msg</p>"; ?>

        <h4>Ganti Email (Target CSRF)</h4>
        <form method="POST" action="profile.php">
            <input type="email" name="email" required placeholder="email@baru.com">
            <button type="submit" name="ganti_email">Update Email</button>
        </form>
        <p><small><em>Note: Form ini tidak memiliki CSRF Token.</em></small></p>
    </div>

</body>

</html>