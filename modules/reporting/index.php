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
    <div class="module-header" style="background: linear-gradient(135deg, #374151, #1f2937);">
        <h1>📝 Reporting dalam Web Penetration Testing</h1>
        <p>Menulis laporan adalah skill terpenting seorang Ethical Hacker / Pentester.</p>
    </div>

    <div class="alert alert-info">
        <strong>💡 Penting:</strong> Menemukan celah saja tidak cukup. Anda harus bisa menjelaskannya kepada pemilik sistem (bisnis) dan developer (teknis) agar bisa diperbaiki.
    </div>

    <style>
        .report-section { margin-bottom: 2rem; border-left: 3px solid var(--primary); padding-left: 1rem; }
        .report-section h3 { margin-top: 0; }
    </style>

    <div style="background: #fff; padding: 2rem; border-radius: var(--radius); box-shadow: var(--shadow);">
        <h2>Struktur Laporan Standar</h2>
        
        <div class="report-section">
            <h3>1. Executive Summary (Ringkasan Eksekutif)</h3>
            <p>Bagian ini ditujukan untuk Manajemen/Direksi (Non-Teknis).</p>
            <ul>
                <li>Jelaskan risiko bisnis (misal: "Data nasabah bisa dicuri").</li>
                <li>Hindari istilah teknis yang terlalu dalam.</li>
                <li>Berikan grafik ringkasan temuan (High, Medium, Low).</li>
            </ul>
        </div>

        <div class="report-section">
            <h3>2. Technical Findings (Temuan Teknis)</h3>
            <p>Bagian ini ditujukan untuk Developer/IT Security.</p>
            <p>Setiap temuan harus memiliki elemen berikut:</p>
            <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; background: #f9f9f9; font-weight: bold;">Judul Vulnerability</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Contoh: SQL Injection pada Login Page</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; background: #f9f9f9; font-weight: bold;">Severity (Tingkat Bahaya)</td>
                    <td style="border: 1px solid #ddd; padding: 8px;"><span style="color:red; font-weight:bold;">CRITICAL</span> / HIGH / MEDIUM / LOW</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; background: #f9f9f9; font-weight: bold;">Deskripsi</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Penjelasan singkat apa itu SQL Injection.</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; background: #f9f9f9; font-weight: bold;">Proof of Concept (PoC)</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Langkah-langkah reproduksi (Screenshot, Payload, HTTP Request).</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ddd; padding: 8px; background: #f9f9f9; font-weight: bold;">Remediation (Solusi)</td>
                    <td style="border: 1px solid #ddd; padding: 8px;">Saran perbaikan (misal: Gunakan Prepared Statements).</td>
                </tr>
            </table>
        </div>

        <div class="report-section">
            <h3>3. Lampiran & Referensi</h3>
            <p>Sertakan referensi CVE (Common Vulnerabilities and Exposures) atau link ke OWASP jika ada.</p>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
