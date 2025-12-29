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
        <div class="nav-label">General</div>
        <a href="<?= $base_path ?>index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>">📊 Dashboard</a>
        
        <div class="nav-label">Modul 1: Injection & Input</div>
        <a href="<?= $base_path ?>modules/sqli/index.php" class="<?= strpos($uri, 'sqli') !== false ? 'active' : '' ?>">
            💉 SQL Injection
        </a>
        <a href="<?= $base_path ?>modules/xss/index.php" class="<?= strpos($uri, 'xss') !== false ? 'active' : '' ?>">
            📢 Cross-Site Scripting (XSS)
        </a>
        <a href="<?= $base_path ?>ssrf.php" class="<?= $current_page == 'ssrf.php' ? 'active' : '' ?>">
            🌐 SSRF
        </a>

        <div class="nav-label">Modul 2: Access Control</div>
        <a href="<?= $base_path ?>idor.php" class="<?= $current_page == 'idor.php' ? 'active' : '' ?>">
            🆔 IDOR
        </a>
        <a href="<?= $base_path ?>admin_panel.php" class="<?= $current_page == 'admin_panel.php' ? 'active' : '' ?>">
            ⛔ Privilege Escalation
        </a>
        <a href="<?= $base_path ?>profile.php" class="<?= $current_page == 'profile.php' ? 'active' : '' ?>">
            🎭 CSRF
        </a>

        <div class="nav-label">Modul 3: Data Protection</div>
        <a href="<?= $base_path ?>modules/crypto/index.php" class="<?= $current_page == 'index.php' && strpos($uri, 'crypto') !== false ? 'active' : '' ?>">
            🔐 Crypto Overview
        </a>
        <a href="<?= $base_path ?>modules/crypto/modul1.php" style="margin-left: 1rem; font-size: 0.9rem;" class="<?= $current_page == 'modul1.php' ? 'active' : '' ?>">
            • Lab AES
        </a>
        <a href="<?= $base_path ?>modules/crypto/modul2.php" style="margin-left: 1rem; font-size: 0.9rem;" class="<?= $current_page == 'modul2.php' ? 'active' : '' ?>">
            • Lab Hashing
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="<?= $base_path ?>logout.php" class="btn-logout">🚪 Logout</a>
    </div>
</aside>
