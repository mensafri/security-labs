# Security Labs: Web Vulnerability Workshop

Selamat datang di **Security Labs**, sebuah platform pembelajaran interaktif untuk memahami kerentanan keamanan web dan teknik *Secure Coding*.

Aplikasi ini dirancang sebagai laboratorium mandiri bagi mahasiswa untuk mempraktikkan serangan siber (Ethical Hacking) dan memahami cara menambalnya.

---

## 📚 Kurikulum Modul

Platform ini dibagi menjadi beberapa modul pembelajaran utama:

### 🔍 Phase 1: Reconnaissance
1.  **Information Gathering:** Mengumpulkan informasi target (`robots.txt`, Source Code Comments).

### 🔑 Phase 2: Authentication
1.  **Bruteforce:** Serangan menebak password.
2.  **SQL Injection (SQLi):**
    *   **Login Bypass:** Manipulasi logika OR.
    *   **UNION Attack:** Teknik dumping database user & password.

### 💻 Phase 3: Server-Side Attacks
1.  **Command Injection:** Eksekusi perintah OS.
2.  **SSTI:** Server-Side Template Injection.
3.  **File Inclusion (LFI):** Membaca file server.
4.  **File Upload:** Upload backdoor.
5.  **SSRF:** Request forgery internal.

### 🎭 Phase 4: Client-Side Attacks
1.  **XSS (Cross-Site Scripting):**
    *   **Reflected:** Script dipantulkan via URL.
    *   **Stored:** Script tersimpan di database Guestbook.
2.  **CSRF (Cross-Site Request Forgery):** Memalsukan aksi pengguna tanpa sepengetahuan mereka.

### 🔓 Phase 5: Access Control
1.  **IDOR (Insecure Direct Object Reference):** Akses data milik user lain lewat manipulasi ID.
2.  **Privilege Escalation:** Mengakses fitur Admin dengan role User biasa.

### 📝 Phase 6: Defense & Reporting
1.  **Cryptography:** Enkripsi (AES) dan Hashing (Secure Passwords).
2.  **Reporting:** Panduan menulis laporan Pentest profesional.

---

## 🛠️ Persiapan & Instalasi

### 1. Requirements
*   Web Server (Apache/Nginx) via XAMPP atau Laragon.
*   PHP 7.4 atau lebih baru.
*   MySQL / MariaDB.

### 2. Instalasi Database
Aplikasi ini membutuhkan database `praktikum_keamanan`.
1.  Buka **phpMyAdmin** atau **HeidiSQL**.
2.  Buat database baru: `praktikum_keamanan`.
3.  **Import File Database:**
    *   Import file **`database.sql`** yang ada di folder ini.
    *   Ini akan membuat tabel standar `users` dan `notes` beserta data dummy.

### 3. Login Default
Gunakan akun berikut untuk simulasi:

| Peran | Username | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `12345` | Memiliki akses penuh. |
| **User** | `korban` | `12345` | Akun standar untuk simulasi serangan. |

> **Rekomendasi:** Gunakan akun `korban` untuk sebagian besar modul guna menguji apakah user biasa bisa menembus batasan keamanan.

---

## 🎓 Petunjuk Penggunaan

1.  **Pilih Modul:** Gunakan **Sidebar** di sebelah kiri untuk navigasi antar materi.
2.  **Baca Tujuan:** Setiap modul diawali dengan penjelasan singkat mengenai kerentanan.
3.  **Simulasi:** Lakukan percobaan serangan pada form atau URL yang disediakan.
4.  **Analisis:** Perhatikan respon sistem. Jika berhasil menembus keamanan, pelajari mengapa hal itu bisa terjadi.
5.  **Mitigasi (Opsional):** Cek file dengan akhiran `_secure.php` (misal `idor_secure.php`) di source code untuk melihat bagaimana cara menambal celah tersebut.

---
*Dibuat untuk tujuan Pendidikan. Penyalahgunaan materi untuk tindakan ilegal di luar tanggung jawab pengembang.*
