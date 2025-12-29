<?php
session_start();
$base_path = './';
include 'db.php';
// IDOR VULNERABILITY: Fetches note based on GET ID without checking ownership
// Example: idor.php?note_id=1 (Accessing Admin's note)

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #db2777, #9d174d);">
        <h1>🔓 Lab Broken Access Control: IDOR</h1>
        <p>Insecure Direct Object Reference.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Klik salah satu catatan Anda di daftar kiri.</li>
            <li>Perhatikan URL di browser: <code>idor.php?note_id=...</code></li>
            <li>Ubah angka ID tersebut menjadi <code>1</code> (Catatan Admin).</li>
            <li>Tekan Enter. Anda akan melihat data rahasia user lain.</li>
        </ol>
    </div>

    <div class="grid-2">
        <!-- List Notes -->
        <div>
            <h3>Catatan Saya</h3>
            <ul style="list-style: none; padding: 0;">
                <?php
                $my_id = $_SESSION['user_id'];
                $result = mysqli_query($conn, "SELECT * FROM notes WHERE user_id = $my_id");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li style='margin-bottom: 0.5rem;'>";
                    echo "<a href='idor.php?note_id=" . $row['id'] . "' class='btn btn-secondary' style='width: 100%; text-align: left;'>📝 " . htmlspecialchars($row['title']) . "</a>";
                    echo "</li>";
                }
                ?>
            </ul>
        </div>

        <!-- Note Detail (VULNERABLE AREA) -->
        <div>
            <h3>Detail Catatan</h3>
            <?php
            if (isset($_GET['note_id'])) {
                $id = $_GET['note_id'];
                
                // VULNERABLE QUERY: No 'AND user_id = $my_id' check!
                $detail = mysqli_query($conn, "SELECT * FROM notes WHERE id = $id");
                $note = mysqli_fetch_assoc($detail);
                
                if ($note) {
                    echo "<div class='card'>";
                    echo "<h2>" . htmlspecialchars($note['title']) . "</h2>";
                    echo "<p>" . htmlspecialchars($note['content']) . "</p>";
                    
                    // Educational feedback
                    if ($note['user_id'] != $my_id) {
                        echo "<div class='alert alert-danger' style='margin-top: 1rem;'>";
                        echo "<strong>⚠️ ANALISIS KERENTANAN:</strong><br>";
                        echo "Anda sedang melihat data milik User ID " . $note['user_id'] . ", padahal ID Anda adalah " . $my_id . ". <br>Ini membuktikan adanya celah <em>Insecure Direct Object Reference (IDOR)</em>.";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<div class='card'><p>Catatan tidak ditemukan!</p></div>";
                }
            } else {
                echo "<p class='text-muted'>Pilih catatan di samping.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>