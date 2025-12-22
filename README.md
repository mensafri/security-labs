# Modul Praktikum Keamanan Web: SSRF, CSRF & Broken Access Control

Selamat datang di repository praktikum keamanan web. Modul ini dirancang untuk mensimulasikan serangan siber pada lingkungan PHP Native dan mempelajari cara memperbaikinya (_Secure Coding_).

**Materi Praktikum:**

1.  **SSRF (Server-Side Request Forgery)**
2.  **Broken Access Control (IDOR & Privilege Escalation)**
3.  **CSRF (Cross-Site Request Forgery)**

---

## 📂 1. Persiapan Lingkungan (Setup)

Sebelum memulai, pastikan lingkungan kerja Anda sudah siap.

### A. Penempatan Folder

Letakkan folder `security-labs` di root server lokal Anda:

- **Pengguna LARAGON:**
  - Path: `C:\laragon\www\security-labs`
  - Akses Browser: `http://security-labs.test`
- **Pengguna XAMPP:**
  - Path: `C:\xampp\htdocs\security-labs`
  - Akses Browser: `http://localhost/security-labs`

### B. Instalasi Database (Wajib)

Kita membutuhkan database untuk menyimpan user, role (hak akses), dan catatan rahasia.

1.  Buka **HeidiSQL** (Laragon) atau **phpMyAdmin** (XAMPP).
2.  Copy dan jalankan query SQL berikut sepenuhnya:

```sql
-- 1. Hapus database lama agar bersih
DROP DATABASE IF EXISTS praktikum_keamanan;

-- 2. Buat database baru
CREATE DATABASE praktikum_keamanan;
USE praktikum_keamanan;

-- 3. Tabel Users (Menyimpan data login & role)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- 4. Tabel Notes (Untuk simulasi data pribadi/IDOR)
CREATE TABLE notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(100),
    content TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 5. Masukkan Data Dummy (Password: 12345)
-- Akun Admin
INSERT INTO users (username, password, email, role) VALUES
('admin', '12345', 'admin@target.com', 'admin');

-- Akun Korban (User Biasa)
INSERT INTO users (username, password, email, role) VALUES
('korban', '12345', 'korban@target.com', 'user');

-- 6. Masukkan Catatan Dummy
INSERT INTO notes (user_id, title, content) VALUES
(1, 'SECRET ADMIN', 'Kode Peluncuran Nuklir: 99-XX-WW. Jangan disebar!'),
(2, 'Diary Korban', 'Hari ini aku belajar hacking di kampus...');

```

### C. Cek Koneksi

Pastikan file `db.php` Anda memiliki pengaturan user/password database yang benar (Default XAMPP/Laragon biasanya user: `root`, password: _kosong_).

---

## ⚔️ MODUL 1: SSRF (Server-Side Request Forgery)

**Konsep:** Memaksa server web untuk melakukan request ke tempat yang tidak seharusnya (misal: jaringan internal atau file sistem).

**Target:** `ssrf.php` (Tools Cek HTTP/Hybrid)

### Langkah Praktikum:

#### Skenario A: Port Scanning (Reconnaissance)

Mendeteksi apakah ada service database yang berjalan di server lokal.

1. Login sebagai user apa saja (misal: `admin` / `12345`).
2. Di Dashboard, klik menu **1. Lab SSRF**.
3. Di kolom input, masukkan: `http://localhost:3306`
4. Klik **Execute Request**.
5. **Analisis:**

- Lihat output di terminal hitam. Jika muncul karakter aneh atau tulisan `MariaDB` / `mysql_native_password`, berarti **Port 3306 Terbuka**.
- Ini membuktikan server web bisa dipaksa "mengobrol" dengan database internal.

#### Skenario B: Access Control Bypass

Mencoba masuk ke halaman rahasia `admin_internal.php` yang memblokir akses browser publik.

1. Coba akses langsung di browser: `http://security-labs.test/admin_internal.php`.

- **Hasil:** ⛔ ACCESS DENIED (Anda ditolak).

2. Kembali ke Lab SSRF (`ssrf.php`).
3. Masukkan URL target tersebut ke kolom input tools SSRF.

- Payload: `http://localhost/security-labs/admin_internal.php` (Sesuaikan domain jika pakai Laragon).

4. **Hasil:** Anda berhasil melihat tulisan **"WELCOME SUPER ADMIN"**.

- **Analisis:** Server mengizinkan request tersebut karena request datang dari "localhost" (server itu sendiri).

#### Skenario C: Source Code Disclosure (Mencuri Kredensial)

Kita akan membaca isi asli file `db.php` untuk mencuri password database.

1. Di Lab SSRF, masukkan payload PHP Wrapper:

- Payload: `php://filter/read=convert.base64-encode/resource=db.php`

2. **Hasil:** Muncul deretan huruf acak (Base64).
3. Copy teks tersebut.
4. Buka situs [Base64Decode.org](https://www.base64decode.org/), Paste, dan Decode.
5. **Analisis:** Anda sekarang bisa melihat kode PHP asli beserta username & password database!

---

## 🔓 MODUL 2: Broken Access Control (BAC)

**Konsep:** Kegagalan sistem dalam membatasi hak akses user. User biasa bisa melakukan hal-hal yang seharusnya hanya boleh dilakukan admin atau pemilik data.

### Skenario A: IDOR (Insecure Direct Object Reference)

_Kasus: Mengintip catatan pribadi milik orang lain._

1. **Login User Biasa:**

- Logout dulu jika sedang login sebagai admin.
- Login sebagai: **`korban`** / `12345`.

2. Di Dashboard, klik menu **2. Lab IDOR**.
3. Klik judul catatan Anda: "Diary Korban".
4. Perhatikan URL di browser:

- `.../idor.php?note_id=2`

5. **Serangan:**

- Ubah angka `2` di URL menjadi `1`.
- Tekan Enter.

6. **Hasil:** Judul berubah menjadi "SECRET ADMIN" dan isinya "Kode nuklir...".

- **Analisis:** Aplikasi hanya mengecek ID di database tanpa memvalidasi siapa pemiliknya.

### Skenario B: Privilege Escalation (Vertical)

_Kasus: User biasa memaksa masuk ke ruang Admin._

1. Pastikan Anda masih login sebagai **`korban`** (Role: User).

- Lihat badge di samping nama user, tertulis "user".

2. Di Dashboard, klik menu **3. Lab Admin Panel**.
3. **Hasil:** Anda berhasil masuk ke halaman dengan peringatan merah "SUPER SECRET ADMIN PANEL".

- **Analisis:** Halaman tersebut hanya mengecek "Apakah user sudah login?", tetapi lupa mengecek "Apakah role user adalah admin?".

---

## 🚫 MODUL 3: CSRF (Cross-Site Request Forgery)

**Konsep:** Penyerang membuat jebakan yang memaksa browser korban mengirimkan request sensitif (ganti email/password) tanpa sepengetahuan korban.

**Target:** `profile.php` (Fitur Ganti Email)

### Langkah Praktikum:

#### 1. Persiapan Penyerang (Hacker)

1. Buka file `attacker.html` menggunakan Text Editor (Notepad/VS Code).
2. Cari baris `<form action="...">`.
3. Ubah URL action agar sesuai dengan server target Anda:

- Laragon: `http://security-labs.test/profile.php`
- XAMPP: `http://localhost/security-labs/profile.php`

4. Simpan file.

#### 2. Kondisi Korban

1. Buka `login.php`.
2. Login sebagai **`admin`** / `12345`.
3. Pastikan email saat ini adalah: `admin@target.com`.
4. **PENTING:** Jangan Logout. Biarkan tab ini terbuka (Simulasi user sedang aktif bekerja).

#### 3. Eksekusi Serangan

1. Buka **Tab Baru** di browser.
2. Jalankan file penyerang: `http://security-labs.test/attacker.html` (atau `localhost`).
3. Website akan berkedip sebentar (JavaScript otomatis mengirim form tersembunyi).

#### 4. Verifikasi Dampak

1. Kembali ke tab Dashboard Admin (`profile.php`).
2. Refresh halaman.
3. **Hasil:** Email admin telah berubah menjadi `hacked_by_attacker@gmail.com`.

---

## 🛡️ Solusi & Perbaikan (Mitigasi)

Setiap kerentanan di atas memiliki versi kode yang aman (_Secure Coding_) di folder ini. Silakan pelajari perbedaannya:

| Jenis Celah  | File Vulnerable   | File Secure              | Teknik Perbaikan                                                                           |
| ------------ | ----------------- | ------------------------ | ------------------------------------------------------------------------------------------ |
| **SSRF**     | `ssrf.php`        | `ssrf_secure.php`        | **Whitelist Domain:** Hanya mengizinkan request ke domain yang terdaftar secara eksplisit. |
| **IDOR**     | `idor.php`        | `idor_secure.php`        | **Ownership Check:** Menambahkan `AND user_id = current_user` pada query SQL.              |
| **Priv Esc** | `admin_panel.php` | `admin_panel_secure.php` | **Role Validation:** Mengecek `$_SESSION['role'] === 'admin'` sebelum menampilkan konten.  |
| **CSRF**     | `profile.php`     | `profile_secure.php`     | **CSRF Token:** Menambahkan token acak unik di form dan memverifikasinya saat submit.      |

---

_Dibuat untuk tujuan edukasi Keamanan Siber._
