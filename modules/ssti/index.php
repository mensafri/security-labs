<?php
session_start();
$base_path = '../../';
include '../../db.php';

// Simulate a Template Engine Vulnerability
// Real world: Smarty, Twig, Jinja2
// Here: preg_replace /e pattern (Old PHP) or assert() or eval()
// For educational safety & clarity, we'll use a custom parser that "accidentally" evals content inside {{ }}

$output = "";
$name = isset($_GET['name']) ? $_GET['name'] : 'Guest';

// VULNERABLE FUNCTION
function render_template($template, $data) {
    // Basic substitution
    $template = str_replace('{{name}}', $data['name'], $template);
    
    // DANGEROUS: Evaluating any {{ php_code }}
    // Simulate what happens in insecure Template Engines (SSTI)
    // We look for {{ code }} and eval it.
    
    return preg_replace_callback('/\{\{(.*?)\}\}/', function($match) {
        // Warning: extremely dangerous in real apps
        try {
            return eval('return ' . $match[1] . ';');
        } catch (Throwable $t) {
            return "Error";
        }
    }, $template);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit;
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="module-header" style="background: linear-gradient(135deg, #a855f7, #7e22ce);">
        <h1>🧩 Server-Side Template Injection (SSTI)</h1>
        <p>Terjadi ketika input pengguna disisipkan ke dalam Template Engine dan dievaluasi sebagai kode.</p>
    </div>

    <div class="alert alert-info">
        <strong>📚 Langkah Percobaan:</strong>
        <p>Aplikasi ini menggunakan sistem template <code>{{ variable }}</code>.</p>
        <ol style="margin-left: 1.5rem; margin-top: 0.5rem;">
            <li>Coba masukkan nama biasa: <code>Budi</code>. Hasil: "Hello, Budi".</li>
            <li>Coba tes matematika: <code>{{ 7 * 7 }}</code>.</li>
            <li>Jika hasilnya <strong>49</strong>, berarti server mengevaluasi ekspresi matematika tersebut (Vulnerable).</li>
            <li><strong>Exploit (RCE):</strong> Coba baca info PHP: <code>{{ phpinfo() }}</code> atau <code>{{ system('whoami') }}</code>.</li>
        </ol>
    </div>

    <div style="background: var(--background); padding: 1.5rem; border-radius: var(--radius); border: 1px solid var(--border); text-align: center;">
        <h3>Template Preview</h3>
        <p>Format Template: <code>Hello, {{name}}!</code></p>
        
        <form method="GET" style="margin-top: 1rem;">
            <input type="text" name="name" placeholder="Masukkan nama..." value="<?= htmlspecialchars($name) ?>">
            <button type="submit" class="btn">Render</button>
        </form>

        <div style="margin-top: 2rem; padding: 1rem; border: 1px dashed var(--primary); background: #fff;">
            <strong>Output:</strong>
            <h2 style="margin: 0.5rem 0;">
                <?php
                // Simulation of rendering a user-controlled template
                $template_string = "Hello, " . $name . "!"; 
                // In SSTI, the user input becomes PART of the template structure
                
                echo render_template("Hello, " . $name . "!", ['name' => 'Demo']);
                ?>
            </h2>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
