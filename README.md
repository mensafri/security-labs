# Modul Praktikum Keamanan Web: SSRF & CSRF (Attack & Defense)

Selamat datang di praktikum keamanan web. Repository ini dirancang untuk mensimulasikan serangan dan pertahanan pada celah keamanan **SSRF (Server-Side Request Forgery)** dan **CSRF (Cross-Site Request Forgery)** menggunakan PHP Native.

Modul ini telah diperbarui dengan teknik serangan **Hybrid** (menggabungkan cURL dan PHP Streams) untuk simulasi yang lebih realistis.

## 📂 Struktur Project

Pastikan file-file berikut tersedia di dalam folder project Anda (`security-labs`):

```text
security-labs/
├── admin_internal.php  # [NEW] Halaman rahasia (Simulasi proteksi internal)
├── attacker.html       # Halaman jebakan (Simulasi website penyerang)
├── db.php              # Koneksi database
├── login.php           # Halaman login user
├── profile.php         # [VULNERABLE] Target CSRF (Tanpa perlindungan)
├── profile_secure.php  # [SECURE] Target CSRF (Dilengkapi CSRF Token)
├── ssrf.php            # [VULNERABLE] Target SSRF (Hybrid: cURL + Streams)
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

### 1\. SSRF (Server-Side Request Forgery)

**File Target:** `ssrf.php`
**Deskripsi:** Tools ini menggunakan logika **Hybrid**. Jika URL adalah HTTP, server menggunakan `cURL`. Jika URL adalah non-HTTP, server menggunakan `file_get_contents`.

#### Skenario A: Port Scanning (Reconnaissance)

Mendeteksi layanan internal yang tidak bisa diakses lewat browser biasa.

- **Payload:** `http://localhost:3306`
- **Hasil:** Server menampilkan respon mentah dari MySQL (contoh: teks aneh berisi `mysql_native_password` atau versi `MariaDB`).
- **Analisis:** Ini membuktikan server web bisa dipaksa "mengobrol" dengan database internal.

#### Skenario B: Access Control Bypass (Admin Jebol)

Mencoba mengakses halaman rahasia `admin_internal.php` yang memblokir akses dari browser publik.

1.  Coba akses langsung di browser: `http://security-labs.test/admin_internal.php`.
    - **Hasil:** ⛔ ACCESS DENIED.
2.  Gunakan SSRF untuk mengaksesnya:
    - **Payload (Laragon):** `http://security-labs.test/admin_internal.php`
    - **Payload (XAMPP):** `http://localhost/security-labs/admin_internal.php`
    - **Hasil:** 🔓 WELCOME SUPER ADMIN. Server mengizinkan request karena berasal dari "localhost" (dirinya sendiri).

#### Skenario C: Source Code Disclosure (Credential Theft)

Mencuri kode asli PHP (termasuk password database) menggunakan PHP Wrapper.

- **Payload:** `php://filter/read=convert.base64-encode/resource=db.php`
- **Hasil:** Muncul deretan huruf acak (Base64).
- **Decode:** Copy teks tersebut -\> Buka [Base64Decode.org](https://www.base64decode.org/) -\> Paste & Decode.
- **Analisis:** Anda sekarang bisa melihat username & password database secara plain text\!

#### Skenario D: Local File Inclusion (LFI)

Membaca file konfigurasi server.

- **Payload:** `file:///C:/Windows/win.ini` (Windows) atau `file:///etc/passwd` (Linux).
- **Hasil:** Isi file tampil di layar.

---

### 2\. CSRF (Cross-Site Request Forgery)

**File Target:** `profile.php`
**File Penyerang:** `attacker.html`
**Konsep:** Memaksa browser user mengirim request ganti email tanpa sepengetahuan user.

#### Persiapan Serangan

Edit file `attacker.html`, sesuaikan baris `<form action="...">` dengan URL Anda:

- _Laragon:_ `http://security-labs.test/profile.php`
- _XAMPP:_ `http://localhost/security-labs/profile.php`

#### Eksekusi

1.  Buka `login.php` -\> Login sebagai **admin** (Pass: `12345`).
2.  Pastikan email saat ini `admin@target.com`. **JANGAN LOGOUT**.
3.  Buka tab baru, jalankan `attacker.html`.
4.  Kembali ke tab profil admin dan refresh.
5.  **Hasil:** Email berubah menjadi `hacked_by_attacker@gmail.com`.

---

## 🛡️ BAGIAN 2: Mitigasi & Pertahanan (Secure)

Di sesi ini, kita akan menguji kode yang sudah diamankan.

### 1\. Pengamanan SSRF (Whitelist & Protocol Check)

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

### 2\. Pengamanan CSRF (CSRF Token)

**File Target:** `profile_secure.php`

#### Persiapan

1.  Login kembali sebagai **admin**.
2.  Akses menu aman: `profile_secure.php`.
3.  Coba ganti email lewat form yang tersedia. **Berhasil** (karena token valid).

#### Uji Serangan

1.  Edit `attacker.html` lagi. Ubah target action menjadi file secure:
    - `.../profile_secure.php`
2.  Buka `attacker.html` di browser.
3.  **Hasil:** Gagal. Muncul pesan _"SERANGAN CSRF TERDETEKSI\!"_.
4.  **Analisis:** Serangan gagal karena `attacker.html` tidak memiliki `csrf_token` yang cocok dengan session user saat ini.

---

## 📝 Ringkasan Teknis

| Jenis Serangan | Mengapa Terjadi?                                                                            | Cara Mencegah (Mitigasi)                                                                          |
| :------------- | :------------------------------------------------------------------------------------------ | :------------------------------------------------------------------------------------------------ |
| **SSRF**       | Server mempercayai input URL user mentah-mentah (baik via `curl` atau `file_get_contents`). | **Whitelist Domain** (Daftar putih domain yang boleh diakses) & Validasi Protokol (Hanya HTTP/S). |
| **CSRF**       | Server tidak memverifikasi apakah request berasal dari form asli atau website orang lain.   | **CSRF Token** (Kode rahasia unik di setiap form) & SameSite Cookie Attribute.                    |
