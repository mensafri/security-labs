# Modul Praktikum Keamanan Web: SSRF & CSRF

Selamat datang di praktikum keamanan web. Repository ini berisi lingkungan simulasi kerentanan **SSRF (Server-Side Request Forgery)** dan **CSRF (Cross-Site Request Forgery)** menggunakan PHP Native.

## 📂 Struktur Project

Pastikan file-file berikut ada di dalam folder project Anda:

```text
security-labs/
├── attacker.html   # Halaman jebakan (disimulasikan sebagai website hacker)
├── db.php          # Koneksi database
├── login.php       # Halaman login user
├── profile.php     # Halaman target serangan CSRF (Ganti Email)
├── ssrf.php        # Halaman target serangan SSRF (Image Fetcher)
└── README.md       # Petunjuk Praktikum ini
```

````

---

## ⚙️ Instalasi & Persiapan Lingkungan

Silakan ikuti panduan di bawah ini sesuai dengan software yang Anda gunakan (Laragon atau XAMPP).

### Opsi A: Pengguna LARAGON

1.  Buka folder **Root** Laragon (biasanya di `C:\laragon\www`).
2.  Buat folder baru bernama `security-labs`.
3.  Masukkan semua file project ke dalam folder tersebut.
4.  Buka aplikasi Laragon, lalu klik **Start All**.
5.  Laragon akan otomatis membuat virtual host.
    - **URL Akses:** `http://security-labs.test`

### Opsi B: Pengguna XAMPP

1.  Buka folder **Root** XAMPP (biasanya di `C:\xampp\htdocs`).
2.  Buat folder baru bernama `security-labs`.
3.  Masukkan semua file project ke dalam folder tersebut.
4.  Buka **XAMPP Control Panel**, nyalakan **Apache** dan **MySQL**.
    - **URL Akses:** `http://localhost/security-labs`

---

## 🗄️ Setup Database (Wajib)

Sebelum memulai, kita perlu menyiapkan database. Langkah ini sama untuk Laragon maupun XAMPP.

1.  Buka **HeidiSQL** (Laragon) atau **phpMyAdmin** (`http://localhost/phpmyadmin` untuk XAMPP).
2.  Buat database baru dan jalankan query berikut:

<!-- end list -->

```sql
-- Hapus database lama jika ada
DROP DATABASE IF EXISTS praktikum_keamanan;

-- Buat database baru
CREATE DATABASE praktikum_keamanan;
USE praktikum_keamanan;

-- Buat tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Insert data dummy (Password: 12345)
INSERT INTO users (username, password, email) VALUES
('admin', '12345', 'admin@target.com'),
('korban', '12345', 'korban@target.com');
```

### Konfigurasi `db.php`

Pastikan isi file `db.php` sesuai. Secara default, XAMPP dan Laragon menggunakan user `root` tanpa password.

```php
<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP/Laragon kosong. Isi jika Anda set password MySQL.
$db   = 'praktikum_keamanan';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) die("Koneksi gagal: " . mysqli_connect_error());
session_start();
?>
```

---

## 🛡️ Modul 1: SSRF (Server-Side Request Forgery)

**Deskripsi:** Penyerang memanipulasi server untuk mengakses data internal atau melakukan scanning jaringan.

**Target:** `ssrf.php`

### Langkah Praktikum:

1.  **Akses Halaman Target:**

    - **Laragon:** Buka `http://security-labs.test/ssrf.php`
    - **XAMPP:** Buka `http://localhost/security-labs/ssrf.php`

2.  **Percobaan 1: Local File Inclusion (LFI)**
    Coba baca file sistem server dengan protokol `file://`. Masukkan URL berikut di kolom input:

    - **Windows:** `file:///C:/Windows/win.ini`
    - **Linux/Mac:** `file:///etc/passwd`

    > **Hasil:** Jika isi file tampil, server rentan.

3.  **Percobaan 2: Port Scanning (Blind SSRF)**
    Cek apakah port database (3306) terbuka di server localhost. Masukkan URL:

    - Payload: `http://localhost:3306`

    > **Hasil:** Jika muncul pesan error aneh (seperti versi MySQL `8.0.xx` atau karakter sampah), berarti port terbuka dan bisa diakses aplikasi.

4.  **Percobaan 3: Membaca Source Code (PHP Wrapper)**
    Kita akan mencoba membaca file `db.php` untuk mencuri password database.

    - Payload: `php://filter/read=convert.base64-encode/resource=db.php`

    > **Langkah:** \> 1. Submit payload di atas.
    > 2\. Copy teks acak yang muncul (Base64).
    > 3\. Decode teks tersebut di [Base64Decode.org](https://www.base64decode.org/).
    > 4\. Anda akan melihat kode asli PHP termasuk kredensial database.

---

## 🔓 Modul 2: CSRF (Cross-Site Request Forgery)

**Deskripsi:** Penyerang memaksa browser korban yang sedang login untuk melakukan aksi tanpa persetujuan (contoh: ganti email).

**Target:** `profile.php`
**File Penyerang:** `attacker.html`

### ⚠️ PENTING: Edit File `attacker.html` Dulu\!

Sebelum memulai, sesuaikan target serangan di file `attacker.html` agar cocok dengan URL server Anda.

1.  Buka file `attacker.html` dengan Text Editor (Notepad/VS Code).
2.  Cari baris `<form action="...">`.
3.  Ubah URL-nya sesuai software Anda:
    - **Jika pakai Laragon:**
      `<form action="http://security-labs.test/profile.php" method="POST" id="evilForm">`
    - **Jika pakai XAMPP:**
      `<form action="http://localhost/security-labs/profile.php" method="POST" id="evilForm">`
4.  Simpan file.

### Langkah Praktikum:

1.  **Login sebagai Korban:**

    - Akses `login.php`.
    - Login dengan user: `admin` | pass: `12345`.
    - Anda akan masuk ke halaman profil. Perhatikan email saat ini: `admin@target.com`.
    - **JANGAN LOGOUT**. Biarkan tab ini terbuka.

2.  **Eksekusi Serangan:**

    - Buka **Tab Baru** di browser.
    - Akses file penyerang:
      - Laragon: `http://security-labs.test/attacker.html`
      - XAMPP: `http://localhost/security-labs/attacker.html`
    - Halaman akan berkedip (JavaScript otomatis submit form).

3.  **Verifikasi:**

    - Kembali ke tab `profile.php` milik korban.
    - Refresh halaman.
    - **Hasil:** Email berubah menjadi `hacked_by_attacker@gmail.com` tanpa persetujuan Anda\!

---

## 📝 Kesimpulan & Perbaikan

### Cara Mencegah SSRF

- Gunakan **Whitelist Domain** (hanya izinkan akses ke domain tertentu).
- Matikan protokol berbahaya (`file://`, `php://`) di `php.ini`.
- Validasi input user secara ketat.

### Cara Mencegah CSRF

- Implementasikan **CSRF Token** pada setiap form (`<input type="hidden" name="csrf_token" ...>`).
- Gunakan cookie dengan flag `SameSite=Strict`.
- Minta password ulang (Re-authentication) untuk aksi sensitif seperti ganti email/password.
````
