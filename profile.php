<?php
// profile.php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Proses ganti email (VULNERABLE: Tidak ada pengecekan Token CSRF)
if (isset($_POST['ganti_email'])) {
    $new_email = $_POST['email'];
    $id = $_SESSION['user_id'];

    mysqli_query($conn, "UPDATE users SET email='$new_email' WHERE id=$id");
    echo "<p style='color:green'>Email berhasil diubah menjadi: $new_email</p>";
}

// Ambil data user terkini
$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
?>

<h1>Halo, <?php echo $user['username']; ?></h1>
<p>Email Anda saat ini: <strong><?php echo $user['email']; ?></strong></p>

<h3>Ganti Email</h3>
<form method="POST" action="profile.php">
    Email Baru: <input type="email" name="email">
    <button type="submit" name="ganti_email">Update Email</button>
</form>

<a href="logout.php">Logout</a>