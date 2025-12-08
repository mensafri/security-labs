<?php
// profile_secure.php
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 1. Generate CSRF Token jika belum ada [cite: 125]
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Proses Ganti Email (Versi Aman)
if (isset($_POST['ganti_email'])) {

    // 2. Validasi Token CSRF 
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("<h1 style='color:red'>SERANGAN CSRF TERDETEKSI! Request ditolak.</h1>");
    }

    $new_email = $_POST['email'];
    $id = $_SESSION['user_id'];

    // Gunakan Prepared Statement untuk keamanan database (Best Practice tambahan)
    $stmt = $conn->prepare("UPDATE users SET email=? WHERE id=?");
    $stmt->bind_param("si", $new_email, $id);
    $stmt->execute();

    echo "<p style='color:green'>Email BERHASIL diubah secara aman menjadi: $new_email</p>";
}

// Ambil data user
$id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);
?>

<h1>Profil Aman (Secure Mode)</h1>
<p>Halo, <strong><?php echo $user['username']; ?></strong></p>
<p>Email saat ini: <strong><?php echo $user['email']; ?></strong></p>

<h3>Ganti Email (Dengan CSRF Token)</h3>
<form method="POST" action="profile_secure.php">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    Email Baru: <input type="email" name="email" required>
    <button type="submit" name="ganti_email">Update Email</button>
</form>

<a href="logout.php">Logout</a>