# Modul Praktikum Keamanan Web: SSRF & CSRF (Attack & Defense)

Selamat datang di praktikum keamanan web. Repository ini dirancang untuk mensimulasikan serangan dan pertahanan pada celah keamanan **SSRF** dan **CSRF** menggunakan PHP Native.

## 📂 Struktur Project

Pastikan file-file berikut tersedia di dalam folder project Anda (`security-labs`):

```text
security-labs/
├── attacker.html       # Halaman jebakan (Simulasi website penyerang)
├── db.php              # Koneksi database
├── login.php           # Halaman login user
├── profile.php         # [VULNERABLE] Target CSRF (Tanpa perlindungan)
├── profile_secure.php  # [SECURE] Target CSRF (Dilengkapi CSRF Token)
├── ssrf.php            # [VULNERABLE] Target SSRF (Tanpa validasi input)
├── ssrf_secure.php     # [SECURE] Target SSRF (Dilengkapi Whitelist Domain)
└── README.md           # Petunjuk praktikum ini
```

---

## ⚙️ Instalasi & Persiapan Lingkungan

Pilih panduan di bawah sesuai aplikasi server lokal yang Anda gunakan.

### Opsi A: Pengguna LARAGON

1.  Buka folder root Laragon (biasanya `C:\laragon\www`).
2.  Buat folder baru bernama `security-labs`.
3.  Masukkan semua file project ke dalamnya.
4.  Klik **Start All** pada Laragon.
    - **URL Akses:** `http://security-labs.test`

### Opsi B: Pengguna XAMPP

1.  Buka folder root XAMPP (biasanya `C:\xampp\htdocs`).
2.  Buat folder baru bernama `security-labs`.
3.  Masukkan semua file project ke dalamnya.
4.  Nyalakan **Apache** & **MySQL** di XAMPP Control Panel.
    - **URL Akses:** `http://localhost/security-labs`

---

## 🗄️ Setup Database (Wajib)

Langkah ini diperlukan untuk menyimpan data user login.

1.  Buka **HeidiSQL** (Laragon) atau **phpMyAdmin** (XAMPP).
2.  Jalankan query SQL berikut:

<!-- end list -->

```sql
-- Reset Database
DROP DATABASE IF EXISTS praktikum_keamanan;
CREATE DATABASE praktikum_keamanan;
USE praktikum_keamanan;

-- Tabel User
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL
);

-- Data Dummy (Password: 12345)
INSERT INTO users (username, password, email) VALUES
('admin', '12345', 'admin@target.com'),
('korban', '12345', 'korban@target.com');
```

**Catatan `db.php`:** Pastikan konfigurasi user/password di file `db.php` sudah sesuai (Default: user `root`, password kosong).

---

## ⚔️ BAGIAN 1: Simulasi Serangan (Vulnerable)

Di sesi ini, kita akan mengeksploitasi kode yang tidak aman.

### [cite_start]1. SSRF (Server-Side Request Forgery) [cite: 37]

**File Target:** `ssrf.php`
[cite_start]**Konsep:** Memanipulasi server untuk mengakses file internal atau scanning port[cite: 8].

- **Percobaan A: Local File Inclusion (LFI)**

  - **Tujuan:** Membaca file sistem server.
  - **Payload:**
    - Windows: `file:///C:/Windows/win.ini`
    - Linux: `file:///etc/passwd`
  - **Hasil:** Isi file konfigurasi sistem akan tampil di layar.

- **Percobaan B: Port Scanning**

  - **Tujuan:** Mengecek apakah database MySQL aktif.
  - **Payload:** `http://localhost:3306`
  - [cite_start]**Hasil:** Jika muncul pesan error aneh (seperti versi MySQL `8.0.xx`), berarti port tersebut terbuka[cite: 9].

### [cite_start]2. CSRF (Cross-Site Request Forgery) [cite: 88]

**File Target:** `profile.php`
**File Penyerang:** `attacker.html`
[cite_start]**Konsep:** Memaksa browser user mengirim request ganti email tanpa sepengetahuan user[cite: 89].

- **Persiapan:**
  Edit file `attacker.html`, sesuaikan baris `<form action="...">` dengan URL Anda:

  - _Laragon:_ `http://security-labs.test/profile.php`
  - _XAMPP:_ `http://localhost/security-labs/profile.php`

- **Langkah Serangan:**

  1.  Buka `login.php` -\> Login sebagai **admin** (Pass: `12345`).
  2.  Pastikan email saat ini `admin@target.com`. **JANGAN LOGOUT**.
  3.  Buka tab baru, jalankan `attacker.html`.
  4.  Kembali ke tab profil admin dan refresh.
  5.  **Hasil:** Email berubah menjadi `hacked_by_attacker@gmail.com`.

---

## 🛡️ BAGIAN 2: Mitigasi & Pertahanan (Secure)

Di sesi ini, kita akan menguji kode yang sudah diamankan.

### [cite_start]1. Pengamanan SSRF (Whitelist & Protocol Check) [cite: 66, 68]

**File Target:** `ssrf_secure.php`

- **Uji Serangan LFI:**
  - Masukkan: `file:///C:/Windows/win.ini`
  - **Hasil:** Gagal. Error: _"Protokol harus HTTP atau HTTPS"_.
- **Uji Serangan Domain Asing:**
  - Masukkan: `http://evil.com/script.php`
  - **Hasil:** Gagal. Error: _"Domain tidak diizinkan"_.
- **Uji Akses Valid:**
  - Masukkan: `http://via.placeholder.com/150`
  - **Hasil:** Berhasil menampilkan gambar (karena domain ada di whitelist).

### [cite_start]2. Pengamanan CSRF (CSRF Token) [cite: 124]

**File Target:** `profile_secure.php`

- **Persiapan:**

  1.  Login kembali sebagai **admin**.
  2.  Akses menu aman: `profile_secure.php`.
  3.  Coba ganti email lewat form yang tersedia. **Berhasil** (karena token valid).

- **Uji Serangan:**

  1.  Edit `attacker.html` lagi. Ubah target action menjadi file secure:
      - `.../profile_secure.php`
  2.  Buka `attacker.html` di browser.
  3.  **Hasil:** Gagal. Muncul pesan _"SERANGAN CSRF TERDETEKSI\!"_.
  4.  **Analisis:** Serangan gagal karena `attacker.html` tidak memiliki `csrf_token` yang cocok dengan session user saat ini.

---

## 📝 Perbandingan Kode

| Fitur                   | Vulnerable                               | Secure                                                         |
| :---------------------- | :--------------------------------------- | :------------------------------------------------------------- |
| **SSRF: Validasi URL**  | Tidak ada (`file_get_contents` langsung) | [cite_start]Whitelist Domain & Cek Protokol HTTP/S [cite: 65]  |
| **CSRF: Proteksi Form** | Tidak ada                                | [cite_start]Token Acak (`$_SESSION['csrf_token']`) [cite: 124] |
| **CSRF: Verifikasi**    | Server menerima request apa saja         | Server menolak request tanpa token valid                       |

---
