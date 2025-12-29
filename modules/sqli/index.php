<?php
session_start();
$base_path = '../../';
include '../../db.php';
// Helper function to simulate vulnerable query
function vulnerable_login($conn, $username, $password) {
    // VULNERABLE: Direct string concatenation!
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    
    // For educational purpose, show the query
    $debug_query = $query;
    
    // Execute multiple queries support is constrained in PHP mysqli usually, 
    // but here we just execute the first one which is enough for ' OR 1=1 
    $result = mysqli_query($conn, $query);
    return ['result' => $result, 'debug' => $debug_query];
}

$login_success = false;
$debug_msg = "";
$flag = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $attempt = vulnerable_login($conn, $user, $pass);
    $debug_msg = $attempt['debug'];
    
    if ($attempt['result'] && mysqli_num_rows($attempt['result']) > 0) {
        $login_success = true;
        // Check if we logged in as admin (id 1) specifically or just bypassed
        $row = mysqli_fetch_assoc($attempt['result']);
        if ($row['username'] === 'admin' || mysqli_num_rows($attempt['result']) > 1) {
             // Admin Access Achieved
             $is_admin_bypass = true;
        }
    }
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}
include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
        <h1>💉 Lab SQL Injection (SQLi)</h1>
        <p>Manipulasi query database untuk login tanpa password!</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Masukkan Username: <code>admin</code></li>
            <li>Masukkan Password: <code>' OR '1'='1</code></li>
            <li>Klik tombol <strong>Login</strong>.</li>
            <li>Perhatikan bahwa Anda berhasil masuk karena query menjadi: <br><code>SELECT * FROM users WHERE username = 'admin' AND password = '' OR '1'='1'</code></li>
        </ol>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        
        <!-- LOGIN FORM -->
        <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border);">
            <h3>Login Admin</h3>
            <form method="POST">
                <label>Username</label>
                <input type="text" name="username" placeholder="admin" required>
                
                <label>Password</label>
                <input type="text" name="password" placeholder="rahasia">
                
                <button type="submit" class="btn" style="width: 100%;">Login</button>
            </form>
        </div>

        <!-- DEBUG VISUALIZER -->
        <div>
            <h3>🔍 SQL Query Debugger</h3>
            <p>Lihat bagaimana input Anda diproses oleh database:</p>
            
            <div style="background: #1e293b; color: #a5b4fc; padding: 1rem; border-radius: var(--radius); font-family: monospace; min-height: 100px;">
                <?php if ($debug_msg): ?>
                    <?= htmlspecialchars($debug_msg) ?>
                <?php else: ?>
                    Waiting for input...
                <?php endif; ?>
            </div>

            <?php if ($login_success): ?>
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <strong>🔓 LOGIN BERHASIL!</strong>
                    <p>Query SQL valid dan database mengembalikan data User.</p>
                    
                    <?php if (isset($is_admin_bypass) && $is_admin_bypass): ?>
                        <div style="margin-top: 1rem; padding: 1rem; background: #dcfce7; border: 2px solid var(--success);">
                            <h3 style="color: var(--success); margin:0;">⚠️ CRITICAL VULNERABILITY</h3>
                            <p>Anda berhasil masuk sebagai <strong>Admin</strong> tanpa mengetahui password!</p>
                            <p>Hal ini terjadi karena input <code>' OR '1'='1</code> mengubah logika query menjadi SELALU BENAR (TRUE).</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
