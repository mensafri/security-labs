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
        
        <div class="nav-label">1. Reconnaissance</div>
        <a href="<?= $base_path ?>modules/info_gathering/index.php" class="<?= strpos($uri, 'info_gathering') !== false ? 'active' : '' ?>">
             Info Gathering
        </a>

        <div class="nav-label">2. Authentication</div>
        <a href="<?= $base_path ?>modules/bruteforce/index.php" class="<?= strpos($uri, 'bruteforce') !== false ? 'active' : '' ?>">
             Bruteforce
        </a>
        <a href="<?= $base_path ?>modules/sqli/index.php" class="<?= $current_page == 'index.php' && strpos($uri, 'sqli') !== false ? 'active' : '' ?>">
            💉 SQLi: Login Bypass
        </a>
        <a href="<?= $base_path ?>modules/sqli/union.php" class="<?= $current_page == 'union.php' ? 'active' : '' ?>" style="margin-left: 1rem; font-size: 0.9rem;">
            • Union Attack
        </a>

        <div class="nav-label">3. Server-Side Attacks</div>
        <a href="<?= $base_path ?>modules/cmd_injection/index.php" class="<?= strpos($uri, 'cmd_injection') !== false ? 'active' : '' ?>">
            💻 Command Injection
        </a>
        <a href="<?= $base_path ?>modules/file_inclusion/index.php" class="<?= strpos($uri, 'file_inclusion') !== false ? 'active' : '' ?>">
            📁 File Inclusion (LFI)
        </a>
        <a href="<?= $base_path ?>modules/file_upload/index.php" class="<?= strpos($uri, 'file_upload') !== false ? 'active' : '' ?>">
            📤 File Upload
        </a>
        <a href="<?= $base_path ?>ssrf.php" class="<?= $current_page == 'ssrf.php' ? 'active' : '' ?>">
            🌐 SSRF
        </a>
        <a href="<?= $base_path ?>modules/ssti/index.php" class="<?= strpos($uri, 'ssti') !== false ? 'active' : '' ?>">
            🧩 SSTI
        </a>

        <div class="nav-label">4. Client-Side Attacks</div>
        <a href="<?= $base_path ?>modules/xss/index.php" class="<?= $current_page == 'index.php' && strpos($uri, 'xss') !== false ? 'active' : '' ?>">
            📢 XSS: Reflected
        </a>
        <a href="<?= $base_path ?>modules/xss/stored.php" class="<?= $current_page == 'stored.php' ? 'active' : '' ?>" style="margin-left: 1rem; font-size: 0.9rem;">
            • Stored (Guestbook)
        </a>
        <a href="<?= $base_path ?>profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">
            🎭 CSRF
        </a>

        <div class="nav-label">5. Access Control</div>
        <a href="<?= $base_path ?>idor.php" class="<?= $current_page == 'idor.php' ? 'active' : '' ?>">
            🆔 IDOR
        </a>
        <a href="<?= $base_path ?>admin_panel.php" class="<?= $current_page == 'admin_panel.php' ? 'active' : '' ?>">
            ⛔ Privilege Escalation
        </a>

        <div class="nav-label">6. Defense & Reporting</div>
        <a href="<?= $base_path ?>modules/crypto/index.php" class="<?= strpos($uri, 'crypto') !== false ? 'active' : '' ?>">
            🔐 Cryptography
        </a>
        <a href="<?= $base_path ?>modules/reporting/index.php" class="<?= strpos($uri, 'reporting') !== false ? 'active' : '' ?>">
            📝 Reporting
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= $base_path ?>logout.php" class="btn-logout">🚪 Logout</a>
    </div>
</aside>
