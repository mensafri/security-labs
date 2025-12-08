<?php
// db.php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default password Laragon kosong
$db   = 'praktikum_keamanan';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
session_start();
?>