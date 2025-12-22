<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) header("Location: login.php");
$current_user_id = $_SESSION['user_id'];
?>

<body>
    <h1>Catatan Pribadi (Secure)</h1>
    <hr>
    <?php
    if (isset($_GET['note_id'])) {
        $id = $_GET['note_id'];

        // PERBAIKAN: Tambahkan "AND user_id = ..."
        // Ini memastikan hanya pemilik asli yang bisa melihat.
        $query = "SELECT * FROM notes WHERE id = $id AND user_id = $current_user_id";
        $detail = mysqli_query($conn, $query);

        if ($data = mysqli_fetch_assoc($detail)) {
            echo "<div class='note' style='border-color:green'>";
            echo "<h2>" . $data['title'] . "</h2>";
            echo "<p>" . $data['content'] . "</p>";
            echo "</div>";
        } else {
            echo "<h3 style='color:red'>AKSES DITOLAK!</h3>";
            echo "<p>Anda tidak berhak melihat catatan ini atau catatan tidak ada.</p>";
        }
    }
    ?>
</body>

</html>