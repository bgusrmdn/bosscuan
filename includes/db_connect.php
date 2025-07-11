<?php
// File: includes/db_connect.php

// Konfigurasi Database
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'hokiraja_db';

// Membuat koneksi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Set karakter set
$conn->set_charset("utf8mb4");
