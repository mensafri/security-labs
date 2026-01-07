# Security Labs: Workshop Keamanan Data & Informasi

Selamat datang di **Security Labs**, sebuah platform pembelajaran interaktif yang dirancang untuk praktikum mata kuliah Keamanan Data dan Informasi. Platform ini mencakup 14 Modul Praktikum mulai dari Kriptografi, Penetration Testing Management, hingga Web Exploitation.

Aplikasi ini bersifat **Educational Sandbox**. Semua kerentanan (SQLi, XSS, dll) dibuat secara sengaja untuk tujuan pembelajaran.

---

## 📚 Sistematika Modul Praktikum

Sesuai dengan kurikulum **Sistematika Penulisan Laporan**, berikut adalah daftar modul yang tersedia:

### 🔐 BAGIAN A: KRIPTOGRAFI

1.  **Modul 1:** Kriptografi Klasik 1 (Caesar & Subsitusi, Vigenere).
2.  **Modul 2:** Kriptografi Klasik 2 (Transposisi).
3.  **Modul 3:** Kriptografi Klasik 3 (Affine & Super Cipher).
4.  **Modul 4:** Stream Cipher (RC4).
5.  **Modul 5:** Block Cipher (Visualisasi Mode Operasi ECB/CBC).
6.  **Modul 6:** Algoritma Modern (DES & AES).
7.  **Modul 7:** Fungsi Hash (MD5, SHA).

### 🛡️ BAGIAN B: KEAMANAN WEB (Pentest)

8.  **Modul 8:** Penetration Testing Management (Pre-Engagement & Scoping Simulator).
9.  **Modul 9:** Instalasi DVWA (Damn Vulnerable Web App) - _Panduan Instalasi Instruktur_.
10. **Modul 10:** Information Gathering (Reconnaissance Tools: Whois, DNS, Headers).
11. **Modul 11:** Cross Site Scripting (XSS) - _Reflected & Stored Guestbook_.
12. **Modul 12:** Command Injection dan File Inclusion (LFI).
13. **Modul 13:** SQL Injection, Brute Force, dan CSRF.
14. **Modul 14:** File Upload, Vulnerability, SSRF, dan Reporting.

---

## 🛠️ Persiapan & Instalasi

### 1. Requirements

- Web Server (Apache/Nginx) via XAMPP.
- PHP 7.4 atau lebih baru.
- MySQL / MariaDB.

### 2. Instalasi Database

Aplikasi ini membutuhkan database `praktikum_keamanan`.

1.  Buka **phpMyAdmin**.
2.  Buat database baru: `praktikum_keamanan`.
3.  Import file **`database.sql`** yang disediakan.

### 3. Akun Default

Gunakan akun berikut untuk simulasi:

| Peran     | Username | Password | Keterangan                            |
| :-------- | :------- | :------- | :------------------------------------ |
| **Admin** | `admin`  | `12345`  | Memiliki akses penuh.                 |
| **User**  | `korban` | `12345`  | Akun standar untuk simulasi serangan. |

---

## 🎓 Fitur Edukasi Baru

- **Show Logic Code:** Pada modul Kriptografi, mahasiswa dapat melihat kode PHP asli di balik algoritma enkripsi.
- **Visual Simulators:** Modul Pentest dilengkapi dengan form simulasi kontrak kerja (Scoping) dan Terminal Linux virtual.
- **Step-by-Step Guide:** Setiap lab dilengkapi dengan "Langkah Percobaan" yang terstruktur.

---

_Dibuat untuk tujuan Pendidikan. Penyalahgunaan materi untuk tindakan ilegal di luar tanggung jawab pengembang._
