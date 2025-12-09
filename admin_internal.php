<?php
// admin_internal.php

// Simulasi: Halaman ini memiliki proteksi IP atau Firewall
// Anggaplah halaman ini hanya boleh diakses oleh 'localhost' via command line/internal request
// Kita gunakan trik sederhana mendeteksi User Agent untuk simulasi lab
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Jika diakses langsung oleh Browser (Chrome/Firefox), tolak!
// (Biasanya browser User-Agent-nya panjang, diawali Mozilla/5.0...)
if (strpos($userAgent, 'Mozilla') !== false) {
    http_response_code(403);
    echo "<h1 style='color:red; font-size:50px;'>⛔ ACCESS DENIED ⛔</h1>";
    echo "<h3>Security Alert:</h3>";
    echo "<p>Halaman ADMIN RAHASIA ini tidak boleh diakses dari Public Browser!</p>";
    echo "<p>IP Anda: " . $_SERVER['REMOTE_ADDR'] . " (Untrusted)</p>";
    exit;
}

// Jika lolos (karena diakses oleh Server via cURL/SSRF yang User-Agentnya beda/kosong)
echo "<div style='border: 5px solid green; padding: 20px; text-align: center;'>";
echo "<h1 style='color:green'>🔓 WELCOME SUPER ADMIN</h1>";
echo "<h3>Ini adalah Data Rahasia Perusahaan:</h3>";
echo "<ul style='text-align:left'>";
echo "<li><strong>Gaji CEO:</strong> Rp 500.000.000 / bulan</li>";
echo "<li><strong>Kode Peluncuran Nuklir:</strong> 99-XX-WW-11</li>";
echo "<li><strong>Token API:</strong> x8s7d87as8d7a8sd78</li>";
echo "</ul>";
echo "</div>";
