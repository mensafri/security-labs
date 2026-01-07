<?php
session_start();
$base_path = '../../';
include '../../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #b91c1c, #991b1b);">
        <h1>💀 Modul 9: Instalasi DVWA</h1>
        <p><strong>Damn Vulnerable Web App (DVWA)</strong> adalah standar emas untuk belajar Web Security.</p>
    </div>

    <div class="alert alert-info">
        <h3>ℹ️ Note Penting</h3>
        <p>Lab "Security Labs" yang sedang Anda gunakan saat ini sebenarnya terinspirasi dari DVWA. 
        Namun, untuk memperdalam ilmu, Anda wajib mencoba menginstall DVWA asli di komputer lokal Anda.</p>
    </div>

    <div class="paper-doc">
        <h2 style="text-align: center;">PANDUAN INSTALASI DVWA</h2>
        
        <h3>1. Persiapan Tools</h3>
        <ul>
            <li>Pastikan Anda sudah memiliki <strong>XAMPP</strong> (Windows) atau <strong>Apache + MySQL</strong> (Linux).</li>
            <li>Download Source Code DVWA terbaru dari GitHub Resmi.</li>
        </ul>

        <div class="terminal-window" style="margin: 1rem 0;">
            <div class="terminal-header">
                <span class="terminal-dot red"></span><span class="terminal-dot yellow"></span><span class="terminal-dot green"></span>
                <span style="color:#aaa; font-size:12px; margin-left: auto;">git clone</span>
            </div>
            <div class="terminal-body">
                <div class="terminal-prompt">
                    <span style="color:#4ade80;">user@pc:~$</span>
                    <input type="text" class="terminal-input" value="git clone https://github.com/digininja/DVWA.git" readonly>
                </div>
            </div>
        </div>

        <h3>2. Konfigurasi Database</h3>
        <ul>
            <li>Buka folder <code>DVWA/config</code>.</li>
            <li>Rename file <code>config.inc.php.dist</code> menjadi <code>config.inc.php</code>.</li>
            <li>Edit file tersebut dan sesuaikan user/pass database (biasanya <code>root</code> / kosong).</li>
        </ul>

        <pre style="background:#eee; padding:1rem; border-radius:4px;">
$_DVWA[ 'db_user' ] = 'root';
$_DVWA[ 'db_password' ] = '';</pre>

        <h3>3. Setup Warning</h3>
        <ul>
            <li>Buka browser dan akses <code>http://localhost/DVWA/setup.php</code>.</li>
            <li>Klik tombol <strong>Create / Reset Database</strong> di bagian bawah.</li>
            <li>Jika sukses, Anda akan diarahkan ke login page.</li>
            <li><strong>Default Login:</strong> User: `admin`, Pass: `password`.</li>
        </ul>

        <h3>4. Tantangan untuk Anda</h3>
        <p>Jika Anda berhasil menginstall DVWA, coba selesaikan level <strong>Command Injection (Low)</strong> di sana dan bandingkan dengan Modul 12 di Lab ini.</p>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
