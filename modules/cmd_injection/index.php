<?php
session_start();
$base_path = '../../';
include '../../db.php';

$output = "";
if (isset($_POST['ip'])) {
    $ip = $_POST['ip'];
    // VULNERABLE: No sanitization!
    // Windows: uses 'ping', Linux uses 'ping -c 4'
    // Warning: Executing commands is dangerous.
    
    // Simple filter to block too dangerous commands for this lab, strictly for safety in this specific env
    // But allowing concatenation chars: & | ;
    if (strpos($ip, 'shutdown') !== false || strpos($ip, 'del') !== false || strpos($ip, 'rm') !== false) {
         $output = "Command blocked for safety reasons.";
    } else {
         // Windows command execution
         $cmd = "ping -n 1 " . $ip;
         $output = shell_exec($cmd);
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #0f172a, #334155);">
        <h1>💻 Command Injection</h1>
        <p>Memanipulasi input untuk mengeksekusi perintah sistem operasi (OS Command).</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Masukkan IP localhost: <code>127.0.0.1</code> normal. Lihat hasilnya.</li>
            <li>Coba gabungkan perintah menggunakan operator penggabung (<code>&</code> atau <code>|</code>).</li>
            <li>Payload: <code>127.0.0.1 & whoami</code> (atau <code>dir</code> di Windows).</li>
            <li>Ini membuktikan Anda bisa mengendalikan server melalui web form ini.</li>
        </ol>
    </div>

    <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
        <h3>📡 Network Connectivity Tester</h3>
        <form method="POST">
            <label>Masukkan IP Address / Hostname:</label>
            <div style="display: flex; gap: 1rem;">
                <input type="text" name="ip" placeholder="127.0.0.1" required style="margin-bottom: 0;">
                <button type="submit" class="btn">Ping</button>
            </div>
        </form>
    </div>

    <?php if ($output): ?>
        <div style="margin-top: 1.5rem; background: #000; color: #0f0; padding: 1rem; border-radius: var(--radius); font-family: monospace; white-space: pre-wrap;">
<?= htmlspecialchars($output) ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>
