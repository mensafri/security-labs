# Security Labs: Web Vulnerability Workshop

Selamat datang di **Security Labs**, sebuah platform pembelajaran interaktif untuk memahami kerentanan keamanan web dan teknik *Secure Coding*.

Aplikasi ini dirancang sebagai laboratorium mandiri bagi mahasiswa untuk mempraktikkan serangan siber (Ethical Hacking) dan memahami cara menambalnya.

---

## 📚 Kurikulum Modul

Platform ini dibagi menjadi beberapa modul pembelajaran utama:

### 💉 Modul 1: Injection & Input Validation
Mempelajari bagaimana input yang tidak divalidasi dapat memanipulasi logika aplikasi.
1.  **SQL Injection (SQLi):** Teknik memanipulasi query database untuk mem-bypass login.
2.  **Cross-Site Scripting (XSS):** Menyisipkan skrip berbahaya ke halaman web yang dilihat pengguna lain.
3.  **SSRF (Server-Side Request Forgery):** Memaksa server untuk mengakses resource internal yang terisolasi.

### 🔓 Modul 2: Broken Access Control
Mempelajari kegagalan aplikasi dalam membatasi hak akses pengguna.
1.  **IDOR (Insecure Direct Object Reference):** Mengakses data pribadi pengguna lain dengan memanipulasi parameter ID.
2.  **Privilege Escalation:** Mengakses fitur administrator menggunakan akun user biasa.
3.  **CSRF (Cross-Site Request Forgery):** Memaksa browser pengguna yang sedang login untuk melakukan tindakan tanpa persetujuan mereka.

### 🔐 Modul 3: Data Protection
Mempelajari teknik enkripsi dan integritas data.
1.  **Kriptografi Simetris (AES):** Simulasi enkripsi dan dekripsi pesan.
2.  **Hashing & Password Security:** Memahami fungsi Hash satu arah dan pentingnya "Salt" untuk keamanan password.

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
