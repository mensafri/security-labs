<!DOCTYPE html>
<html>

<head>
    <title>Praktikum SSRF</title>
</head>

<body>
    <h1>Latihan SSRF (Image/Content Fetcher)</h1>
    <p>Masukkan URL gambar atau website untuk ditampilkan:</p>

    <form method="GET">
        <input type="text" name="url" placeholder="http://example.com" style="width: 300px;">
        <button type="submit">Ambil Data</button>
    </form>

    <hr>

    <?php
    if (isset($_GET['url'])) {
        $url = $_GET['url'];

        echo "<h3>Hasil Fetching:</h3>";

        // VULNERABLE CODE: Menggunakan file_get_contents tanpa validasi
        // Ini sesuai dengan penyebab SSRF pada source [cite: 59]
        $content = @file_get_contents($url);

        if ($content) {
            // Tampilkan sebagai gambar jika memungkinkan, atau text biasa
            echo "<textarea rows='20' cols='80'>" . htmlspecialchars($content) . "</textarea>";
        } else {
            echo "Gagal mengambil data.";
        }
    }
    ?>
</body>

</html>