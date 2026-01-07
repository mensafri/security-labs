<?php
session_start();
$base_path = '../../../';
include '../../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../login.php");
    exit;
}

include '../../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #475569, #1e293b);">
        <h1>📜 Kriptografi Klasik</h1>
        <p>Pelajari algoritma enkripsi klasik yang menjadi dasar kriptografi modern.</p>
    </div>

    <div class="grid-2">
        <!-- Modul 1 -->
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <h3>Modul 1: Substitusi & Vigenere</h3>
            <p>Algoritma dasar penggantian huruf.</p>
            <div style="margin-top: 1rem;">
                <a href="caesar.php" class="btn btn-sm">Caesar Cipher</a>
                <a href="vigenere.php" class="btn btn-sm">Vigenere Cipher</a>
            </div>
        </div>

        <!-- Modul 2 -->
        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <h3>Modul 2: Transposisi</h3>
            <p>Mengacak posisi huruf (bukan mengganti).</p>
            <div style="margin-top: 1rem;">
                <a href="transposition.php" class="btn btn-sm">Transposition Cipher</a>
            </div>
        </div>

        <!-- Modul 3 -->
        <div class="card" style="border-left: 4px solid #ec4899;">
            <h3>Modul 3: Advanced Classic</h3>
            <p>Substitusi & Transposisi Tingkat Lanjut.</p>
            <div style="margin-top: 1rem;">
                <a href="affine.php" class="btn btn-sm">Affine Cipher</a>
                <a href="super.php" class="btn btn-sm">Super Cipher</a>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>
