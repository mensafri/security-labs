<!DOCTYPE html>
<html>

<head>
    <title>SSRF Aman (Secure)</title>
</head>

<body>
    <h1>Latihan SSRF (Versi Aman)</h1>
    <p>Hanya mengizinkan akses ke domain tertentu (Whitelist).</p>

    <form method="GET">
        <input type="text" name="url" placeholder="http://via.placeholder.com/150" style="width: 300px;">
        <button type="submit">Ambil Data</button>
    </form>
    <hr>

    <?php
    if (isset($_GET['url'])) {
        $url = $_GET['url'];

        // 1. Validasi Input Kosong
        if (empty($url)) {
            die("URL tidak boleh kosong.");
        }

        // 2. Parsing URL
        $parsed = parse_url($url);

        // 3. Validasi Protokol (Hanya izinkan HTTP/HTTPS) 
        if (!isset($parsed['scheme']) || !in_array($parsed['scheme'], ['http', 'https'])) {
            die("<p style='color:red'>Error: Protokol harus HTTP atau HTTPS!</p>");
        }

        // 4. Whitelist Domain (Hanya izinkan domain terpercaya) 
        $allowed_domains = ['via.placeholder.com', 'google.com', 'www.google.com'];

        if (!isset($parsed['host']) || !in_array($parsed['host'], $allowed_domains)) {
            die("<p style='color:red'>Error: Domain tidak diizinkan! Hanya boleh ke: " . implode(', ', $allowed_domains) . "</p>");
        }

        // Jika lolos semua validasi, baru eksekusi
        echo "<h3>Hasil Fetching (Aman):</h3>";
        $content = @file_get_contents($url);

        if ($content) {
            echo "<textarea rows='20' cols='80'>" . htmlspecialchars($content) . "</textarea>";
        } else {
            echo "Gagal mengambil data atau koneksi ditolak.";
        }
    }
    ?>
</body>

</html>