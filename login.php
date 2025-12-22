<?php
// login.php
include 'db.php';

// Jika user sudah login, langsung alihkan ke profile
if (isset($_SESSION['user_id'])) {
    header("Location: profile.php");
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
        header("Location: profile.php");
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
    <title>Login - Security Labs</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f2f5;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .hint {
            font-size: 12px;
            color: #666;
            margin-top: 15px;
            text-align: center;
            background: #eee;
            padding: 5px;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h2>Login Labs</h2>

        <?php if ($error): ?>
            <div class="error-msg"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" placeholder="admin / korban" required autofocus>

            <label>Password</label>
            <input type="password" name="password" placeholder="12345" required>

            <button type="submit" name="login">Masuk</button>
        </form>

        <div class="hint">
            <strong>Akun Demo:</strong><br>
            Admin: <code>admin</code> / <code>12345</code><br>
            User: <code>korban</code> / <code>12345</code>
        </div>
    </div>

</body>

</html>