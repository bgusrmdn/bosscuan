<?php

// File: hokiraja/api_get_games.php (REVISI PROFESIONAL: Memastikan Filter Kategori Bekerja)
header('Content-Type: application/json');
require_once 'includes/db_connect.php';

// Ambil parameter filter dari request JavaScript
$category = isset($_GET['category']) && $_GET['category'] !== 'all' ? $_GET['category'] : null;
$provider = isset($_GET['provider']) && $_GET['provider'] !== 'all' ? $_GET['provider'] : null;
$search = isset($_GET['search']) && !empty($_GET['search']) ? $_GET['search'] : null;

// Bangun query SQL secara dinamis berdasarkan filter
$sql = "SELECT nama_game, provider, gambar_thumbnail FROM games WHERE is_active = 1";
$params = [];
$types = "";

// Tambahkan filter kategori jika ada
if ($category) {
    $sql .= " AND kategori = ?";
    $params[] = $category;
    $types .= "s";
}
// Tambahkan filter provider jika ada
if ($provider) {
    $sql .= " AND provider = ?";
    $params[] = $provider;
    $types .= "s";
}
// Tambahkan filter pencarian jika ada
if ($search) {
    $sql .= " AND nama_game LIKE ?";
    $params[] = "%" . $search . "%";
    $types .= "s";
}

$sql .= " ORDER BY nama_game ASC LIMIT 100"; // Batasi hasil untuk performa

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    // Gunakan splat operator (...) untuk unpacking array $params
    // Pastikan urutan parameter sesuai dengan urutan '?' di $sql
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$games = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Pastikan URL gambar dibentuk dengan benar
        $row['gambar_thumbnail_url'] = 'assets/images/games/' . htmlspecialchars($row['gambar_thumbnail']);
        $games[] = $row;
    }
}

$stmt->close();
$conn->close();

echo json_encode($games);
