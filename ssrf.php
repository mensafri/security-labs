<!DOCTYPE html>
<html lang="id">

<head>
    <title>Praktikum SSRF - Advanced Hybrid</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            max-width: 900px;
            margin: 30px auto;
            background: #f4f4f4;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
        }

        .input-group {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #2980b9;
        }

        .terminal {
            background: #1e1e1e;
            color: #00ff00;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin-top: 20px;
        }

        .log-entry {
            margin-bottom: 5px;
            border-bottom: 1px dashed #444;
            padding-bottom: 5px;
        }

        .label {
            color: #00ffff;
            font-weight: bold;
        }

        .error {
            color: #ff5555;
        }

        .warning {
            color: #ffcc00;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>🌐 Server Utility (Hybrid)</h1>
        <p>Tools ini menggunakan <strong>cURL</strong> untuk HTTP dan <strong>PHP Streams</strong> untuk protokol lokal.</p>

        <div class="input-group">
            <form method="GET">
                <input type="text" name="url" placeholder="Contoh: http://google.com atau php://filter/..." value="<?= isset($_GET['url']) ? htmlspecialchars($_GET['url']) : '' ?>">
                <button type="submit">Execute Request</button>
            </form>
        </div>

        <?php
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = $_GET['url'];

            echo "<div class='terminal'>";
            echo "<div class='log-entry'>[INFO] Menerima perintah: <span style='color:white'>$url</span></div>";

            // LOGIKA HYBRID
            // Cek apakah protokolnya HTTP/HTTPS?
            if (preg_match('/^https?:\/\//i', $url)) {
                // --- MODE 1: cURL (Bagus untuk Port Scanning & Web) ---
                echo "<div class='log-entry'>[MODE] Terdeteksi protokol HTTP/S -> Menggunakan engine <strong>cURL</strong></div>";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3);
                curl_setopt($ch, CURLOPT_HEADER, true); // Tampilkan Header
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);
                $error_msg = curl_error($ch);

                if ($error_msg) {
                    echo "<div class='log-entry error'>[FAIL] cURL Error: $error_msg</div>";
                } else {
                    echo "<div class='log-entry'>[SUCCESS] HTTP Request Berhasil.</div>";
                    echo "<pre style='color:white;'>" . htmlspecialchars(substr($response, 0, 800)) . "...</pre>";
                }
                curl_close($ch);
            } else {
                // --- MODE 2: PHP Streams (Bagus untuk LFI & Source Code Leak) ---
                echo "<div class='log-entry warning'>[MODE] Protokol Non-HTTP terdeteksi -> Menggunakan engine <strong>file_get_contents()</strong></div>";
                echo "<div class='log-entry'>[INFO] Mencoba membaca resource stream...</div>";

                // Suppress error (@) agar tidak bocor path asli server di browser
                $content = @file_get_contents($url);

                if ($content !== false) {
                    echo "<div class='log-entry'>[SUCCESS] Stream berhasil dibaca!</div>";
                    echo "<div class='log-entry'>[DATA PREVIEW]:</div>";
                    echo "<textarea style='width:100%; height:150px; background:#333; color:white; border:none; padding:10px;'>" . htmlspecialchars($content) . "</textarea>";
                } else {
                    echo "<div class='log-entry error'>[FAIL] Gagal membaca resource. Pastikan file ada atau protokol didukung.</div>";
                    // Tampilkan error terakhir untuk debug mahasiswa
                    $error = error_get_last();
                    if ($error) {
                        echo "<div class='log-entry error'>[DEBUG] System Error: " . $error['message'] . "</div>";
                    }
                }
            }
            echo "</div>";
        }
        ?>

        <div style="margin-top: 20px; padding: 10px; background: #e8f4fd; border: 1px solid #b6e0fe;">
            <strong>🎯 Panduan Payload:</strong>
            <ul>
                <li><small>cURL Mode:</small> <code>http://localhost:3306</code> (Cek Port MySQL)</li>
                <li><small>cURL Mode:</small> <code>http://security-labs.test/admin_internal.php</code> (Bypass Admin)</li>
                <li><small>Stream Mode:</small> <code>file:///C:/Windows/win.ini</code> (Baca File Windows)</li>
                <li><small>Stream Mode:</small> <code>php://filter/read=convert.base64-encode/resource=db.php</code> (Baca Source Code)</li>
            </ul>
        </div>
    </div>

</body>

</html>