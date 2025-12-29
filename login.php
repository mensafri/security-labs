<?php
// login.php
session_start();
include 'db.php';

// Jika user sudah login, langsung alihkan ke profile
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query untuk mengambil data user beserta role-nya
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Simpan data penting ke Session
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role']     = $row['role']; // PENTING: Untuk materi Broken Access Control

        // Redirect ke halaman profil
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Security Labs</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 5vh;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <h2 style="text-align: center; margin-bottom: 2rem;">🔐 Login Labs</h2>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Username</label>
                <input type="text" name="username" placeholder="admin / korban" required autofocus>
            </div>

            <div>
                <label>Password</label>
                <input type="password" name="password" placeholder="12345" required>
            </div>

            <button type="submit" name="login" class="btn" style="width: 100%;">Masuk</button>
        </form>

        <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
            <p style="font-size: 0.875rem; color: var(--text-muted); text-align: center; margin-bottom: 0.5rem;">Akun Demo:</p>
            <div style="display: flex; gap: 1rem; justify-content: center; font-size: 0.875rem;">
                <code>admin / 12345</code>
                <code>korban / 12345</code>
            </div>
        </div>
    </div>

</body>
</html>