<?php
// includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
// Determine active module based on path
$uri = $_SERVER['REQUEST_URI'];
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <div class="logo">🛡️ SecurityLabs</div>
        <div class="user-info">
            <div class="avatar"><?= strtoupper(substr($_SESSION['username'] ?? 'G', 0, 1)) ?></div>
            <div>
                <div class="username"><?= htmlspecialchars($_SESSION['username'] ?? 'Mahasiswa') ?></div>
                <div class="score" style="color: var(--text-muted); font-weight: normal;">Role: <?= htmlspecialchars($_SESSION['role'] ?? 'Guest') ?></div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="<?= $base_path ?>index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">📊 Dashboard</a>

        <div class="nav-label">Kriptografi (Modul 1-7)</div>
        
        <!-- Modul 1: Klasik 1 -->
        <a href="<?= $base_path ?>modules/crypto/classic/index.php" class="<?= strpos($uri, 'crypto/classic') !== false ? 'active' : '' ?>">
            Modul 1: Kriptografi Klasik 1-3
        </a>
        <div style="padding-left: 1rem; font-size: 0.85rem;">
            <a href="<?= $base_path ?>modules/crypto/classic/caesar.php" style="border:none">• Modul 1: Caesar/Substitusi</a>
            <a href="<?= $base_path ?>modules/crypto/classic/vigenere.php" style="border:none">• Modul 1: Vigenere</a>
            <a href="<?= $base_path ?>modules/crypto/classic/transposition.php" style="border:none">• Modul 2: Transposisi</a>
            <a href="<?= $base_path ?>modules/crypto/classic/affine.php" style="border:none">• Modul 3: Affine</a>
            <a href="<?= $base_path ?>modules/crypto/classic/super.php" style="border:none">• Modul 3: Super Cipher</a>
        </div>

        <!-- Modul 4 & 5 & 6 -->
        <a href="<?= $base_path ?>modules/crypto/stream/rc4.php" class="<?= strpos($uri, 'rc4.php') !== false ? 'active' : '' ?>">
            Modul 4: Stream Cipher (RC4)
        </a>
        <a href="<?= $base_path ?>modules/crypto/block/modes.php" class="<?= strpos($uri, 'modes.php') !== false ? 'active' : '' ?>">
            Modul 5: Block Cipher
        </a>
        <a href="<?= $base_path ?>modules/crypto/block/des.php" class="<?= strpos($uri, 'des.php') !== false ? 'active' : '' ?>">
            Modul 6: Admin DES & AES
        </a>
        <a href="<?= $base_path ?>modules/crypto/modul2.php" class="<?= strpos($uri, 'modul2.php') !== false ? 'active' : '' ?>">
            Modul 7: Fungsi Hash
        </a>

        <div class="nav-label">Keamanan Web (Modul 8-14)</div>

        <a href="<?= $base_path ?>modules/pentest/index.php" class="<?= strpos($uri, 'pentest') !== false ? 'active' : '' ?>">
            Modul 8 : Penetration Testing
        </a>
        <a href="<?= $base_path ?>modules/dvwa/index.php" class="<?= strpos($uri, 'dvwa') !== false ? 'active' : '' ?>">
            Modul 9 : Instalasi DVWA
        </a>
        <a href="<?= $base_path ?>modules/info_gathering/index.php" class="<?= strpos($uri, 'info_gathering') !== false ? 'active' : '' ?>">
            Modul 10 : Information Gathering
        </a>
        <a href="<?= $base_path ?>modules/xss/index.php" class="<?= strpos($uri, 'xss') !== false ? 'active' : '' ?>">
            Modul 11 : Cross Site Scripting (XSS)
        </a>
        <a href="<?= $base_path ?>modules/cmd_injection/index.php" class="<?= strpos($uri, 'cmd_injection') !== false ? 'active' : '' ?>">
            Modul 12 : Command Injection dan File Inclusion
        </a>
        <!-- Sub-menu for File Inclusion linking to Modul 12 context -->
        <div style="padding-left: 1rem; font-size: 0.85rem;">
            <a href="<?= $base_path ?>modules/file_inclusion/index.php" style="border:none">• File Inclusion (Praktek)</a>
        </div>

        <a href="<?= $base_path ?>modules/sqli/index.php" class="<?= strpos($uri, 'sqli') !== false ? 'active' : '' ?>">
            Modul 13 : SQL, Brute Force dan CSRF
        </a>
        <div style="padding-left: 1rem; font-size: 0.85rem;">
            <a href="<?= $base_path ?>modules/bruteforce/index.php" style="border:none">• Brute Force</a>
            <a href="<?= $base_path ?>profile.php" style="border:none">• CSRF</a>
        </div>

        <a href="<?= $base_path ?>modules/file_upload/index.php" class="<?= strpos($uri, 'file_upload') !== false ? 'active' : '' ?>">
            Modul 14 : File Upload, Vulnerability, SSRF, dan Reporting
        </a>
        <div style="padding-left: 1rem; font-size: 0.85rem;">
             <a href="<?= $base_path ?>ssrf.php" style="border:none">• SSRF</a>
             <a href="<?= $base_path ?>modules/reporting/index.php" style="border:none">• Reporting</a>
        </div>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= $base_path ?>logout.php" class="btn-logout">🚪 Logout</a>
    </div>
</aside>
