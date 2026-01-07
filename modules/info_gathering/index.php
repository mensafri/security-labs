<?php
session_start();
$base_path = '../../';
include '../../db.php';

// Logic for Simulated Tools
$tool_output = "";
$active_tool = isset($_GET['tool']) ? $_GET['tool'] : '';
$target = isset($_POST['target']) ? $_POST['target'] : 'security-labs.local';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($active_tool == 'whois') {
        $tool_output = "Domain Name: SECURITY-LABS.LOCAL\n";
        $tool_output .= "Registry Domain ID: 99281-ID\n";
        $tool_output .= "Registrar URL: http://www.security-labs.local\n";
        $tool_output .= "Updated Date: 2024-01-01T10:00:00Z\n";
        $tool_output .= "Admin Email: admin@security-labs.local\n"; // Useful info
        $tool_output .= "Admin Phone: +62.812345678\n";
        $tool_output .= "Tech Email: devops@security-labs.local\n"; // Useful info
    } elseif ($active_tool == 'dns') {
        $tool_output = ";; QUESTION SECTION:\n";
        $tool_output .= ";security-labs.local. IN A\n\n";
        $tool_output .= ";; ANSWER SECTION:\n";
        $tool_output .= "security-labs.local. 300 IN A 192.168.1.10\n";
        $tool_output .= "www.security-labs.local. 300 IN A 192.168.1.10\n";
        $tool_output .= "dev.security-labs.local. 300 IN A 192.168.1.11\n"; // Hidden subdomain
        $tool_output .= "mail.security-labs.local. 300 IN A 192.168.1.12\n";
    } elseif ($active_tool == 'headers') {
        $tool_output = "HTTP/1.1 200 OK\n";
        $tool_output .= "Date: " . date(DATE_RFC2822) . "\n";
        $tool_output .= "Server: Apache/2.4.41 (Ubuntu)\n";
        $tool_output .= "X-Powered-By: PHP/7.4.3\n"; // Version leak
        $tool_output .= "Set-Cookie: PHPSESSID=af82...; path=/\n";
        $tool_output .= "X-Debug-Mode: Enabled\n"; // Vuln hint
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
        <h1>🔍 Modul 10: Information Gathering</h1>
        <p>Praktek mengumpulkan informasi target (Reconnaissance) menggunakan simulated tools.</p>
    </div>

    <!-- Navigation Tabs for Tools -->
    <div style="margin-bottom: 2rem; border-bottom: 1px solid #ddd;">
        <a href="?tool=manual" class="btn btn-sm <?= $active_tool == '' || $active_tool == 'manual' ? 'btn-primary' : 'btn-secondary' ?>">1. Manual Recon</a>
        <a href="?tool=whois" class="btn btn-sm <?= $active_tool == 'whois' ? 'btn-primary' : 'btn-secondary' ?>">2. Whois Lookup</a>
        <a href="?tool=dns" class="btn btn-sm <?= $active_tool == 'dns' ? 'btn-primary' : 'btn-secondary' ?>">3. DNS Enumeration</a>
        <a href="?tool=headers" class="btn btn-sm <?= $active_tool == 'headers' ? 'btn-primary' : 'btn-secondary' ?>">4. HTTP Headers</a>
    </div>

    <div class="grid-2">
        <!-- Interactive Tool Area -->
        <div>
            <?php if ($active_tool == '' || $active_tool == 'manual'): ?>
                <h3>1. Manual Inspection</h3>
                <p>Cari informasi rahasia yang tersembunyi di halaman ini tanpa tools.</p>
                <div class="alert alert-info">
                    <strong>Misi:</strong> Temukan komentar developer di Source Code.
                </div>
                <!-- COMMENT: Password backup database ada di /backup/db_2024.sql -->
                <p>Klik kanan > <strong>View Page Source</strong> untuk memulai.</p>
                <p>Coba juga akses: <code>/robots.txt</code> di browser Anda.</p>

            <?php else: ?>
                <h3>Simulasi Terminal: <?= strtoupper($active_tool) ?></h3>
                
                <div class="terminal-window">
                    <div class="terminal-header">
                        <span class="terminal-dot red"></span>
                        <span class="terminal-dot yellow"></span>
                        <span class="terminal-dot green"></span>
                        <span style="margin-left:auto; font-size: 0.8rem; color: #64748b;">kali-linux — <?= $active_tool ?></span>
                    </div>
                    <div class="terminal-body">
                        <form method="POST" action="?tool=<?= $active_tool ?>">
                            <div class="terminal-prompt">
                                <span style="color: #4ade80;">root@kali:~#</span>
                                <span style="color: #60a5fa;"><?= $active_tool ?></span>
                                <input type="text" name="target" value="security-labs.local" class="terminal-input">
                            </div>
                            <button type="submit" style="display:none;"></button> <!-- Enter to submit -->
                        </form>

                        <?php if ($tool_output): ?>
                            <div class="terminal-output"><?= htmlspecialchars($tool_output) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div style="margin-top: 1rem; text-align: right;">
                    <button onclick="document.querySelector('form').submit()" class="btn btn-sm">Jalankan Perintah</button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Instructional Side -->
        <div>
            <?php if ($active_tool == 'whois'): ?>
                <div class="alert alert-success">
                    <h3>🎯 Misi: Whois</h3>
                    <p>Gunakan tool Whois di kiri untuk mencari <strong>Email Admin</strong>.</p>
                    <p>Dalam pentest nyata, email ini digunakan untuk enumerasi username atau target serangan Phishing.</p>
                </div>
            <?php elseif ($active_tool == 'dns'): ?>
                <div class="alert alert-success">
                    <h3>🎯 Misi: Subdomain</h3>
                    <p>Gunakan tool DNS untuk mencari subdomain tersembunyi (misal: `dev` atau `test`).</p>
                    <p>Subdomain development seringkali memiliki keamanan yang lebih lemah.</p>
                </div>
            <?php elseif ($active_tool == 'headers'): ?>
                <div class="alert alert-success">
                    <h3>🎯 Misi: Banner Grabbing</h3>
                    <p>Server memberikan informasi tentang dirinya melalui HTTP Response Headers.</p>
                    <p>Cari tahu: <strong>Versi PHP</strong> dan apakah <strong>Debug Mode</strong> aktif?</p>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <h3>📚 Teori Manual Recon</h3>
                    <p>Seringkali developer meninggalkan jejak penting:</p>
                    <ul>
                        <li><strong>HTML Comments:</strong> <code>&lt;!-- TODO: Fix bug --&gt;</code></li>
                        <li><strong>Robots.txt:</strong> Memberitahu area mana yang tidak boleh di-index (justru memberitahu hacker folder rahasia!).</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
