<?php
include 'db.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>Broken Access Control - IDOR</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .note {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            background: #f9f9f9;
        }

        .warning {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Catatan Pribadi (Vulnerable IDOR)</h1>
    <p>Halo, <?= $_SESSION['username'] ?>. Klik judul untuk membaca catatan.</p>

    <h3>Daftar Catatan Anda:</h3>
    <ul>
        <?php
        // Tampilkan list hanya milik user yang login (Ini sudah benar)
        $q = mysqli_query($conn, "SELECT * FROM notes WHERE user_id = $current_user_id");
        while ($row = mysqli_fetch_assoc($q)) {
            echo "<li><a href='?note_id=" . $row['id'] . "'>" . $row['title'] . "</a></li>";
        }
        ?>
    </ul>

    <hr>

    <?php
    // BAGIAN VULNERABLE
    // Menampilkan detail catatan berdasarkan GET 'note_id'
    if (isset($_GET['note_id'])) {
        $id = $_GET['note_id'];

        // PERHATIKAN: Query ini TIDAK mengecek kepemilikan (user_id)!
        // Selama ID-nya ada di database, data akan ditampilkan.
        $detail = mysqli_query($conn, "SELECT * FROM notes WHERE id = $id");

        if ($data = mysqli_fetch_assoc($detail)) {
            echo "<div class='note'>";
            echo "<h2>" . $data['title'] . "</h2>";
            echo "<p>" . $data['content'] . "</p>";
            echo "<small>Note ID: " . $data['id'] . "</small>";
            echo "</div>";
        } else {
            echo "Catatan tidak ditemukan.";
        }
    }
    ?>
</body>

</html>