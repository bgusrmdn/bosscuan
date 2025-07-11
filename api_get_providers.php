<?php

// File: api_get_providers.php (REVISI FINAL: Memastikan SEMUA provider aktif tampil)

header('Content-Type: application/json');
require_once 'includes/db_connect.php';

// Ambil parameter kategori dari request JavaScript
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

$sql = "";
$stmt = null;

// Jika kategori adalah 'all', ambil semua provider yang memiliki game aktif.
if ($category === 'all' || empty($category)) {
    // Pastikan tidak ada LIMIT di sini untuk mengambil semua data
    $sql = "SELECT DISTINCT p.nama_provider, p.logo_provider, p.sort_order
            FROM providers p
            INNER JOIN games g ON p.nama_provider = g.provider
            WHERE g.is_active = 1
            ORDER BY p.sort_order ASC, p.id ASC"; // Query sudah benar tanpa LIMIT
    $stmt = $conn->prepare($sql);
} else {
    // Jika kategori spesifik dipilih, ambil provider untuk kategori tersebut.
    $sql = "SELECT DISTINCT p.nama_provider, p.logo_provider, p.sort_order
            FROM providers p
            INNER JOIN games g ON p.nama_provider = g.provider
            WHERE g.kategori = ? AND g.is_active = 1
            ORDER BY p.sort_order ASC, p.id ASC"; // Query sudah benar tanpa LIMIT
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $category);
}

$stmt->execute();
$result = $stmt->get_result();

$providers = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Ambil URL logo dengan path yang benar
        $row['logo_provider_url'] = 'assets/images/providers/' . htmlspecialchars($row['logo_provider']);
        $providers[] = $row;
    }
}

$stmt->close();
$conn->close();

// Kembalikan data dalam format JSON
echo json_encode($providers);
