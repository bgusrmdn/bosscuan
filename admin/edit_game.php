<?php
// File: admin/edit_game.php

$page_title = "Ubah Data Game";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Cek apakah ID game ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "ID Game tidak valid.";
    header("Location: manage_games.php");
    exit();
}

$game_id = $_GET['id'];

// Logika saat form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nama_game = $_POST['nama_game'];
    $provider = $_POST['provider'];
    $kategori = $_POST['kategori'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $old_image = $_POST['old_image']; // Ambil nama gambar lama

    $gambar_thumbnail = $old_image; // Defaultnya adalah gambar lama

    // Logika upload gambar baru jika ada
    if (isset($_FILES['gambar_thumbnail']) && $_FILES['gambar_thumbnail']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/games/';
        $original_filename = basename($_FILES["gambar_thumbnail"]["name"]);
        $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["gambar_thumbnail"]["tmp_name"], $target_file)) {
                $gambar_thumbnail = $unique_filename; // Gunakan nama file baru
                // Hapus gambar lama jika ada
                if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                    unlink($target_dir . $old_image);
                }
            }
        }
    }

    // Update data ke database
    $stmt = $conn->prepare("UPDATE games SET nama_game = ?, provider = ?, kategori = ?, gambar_thumbnail = ?, is_featured = ?, is_active = ? WHERE id = ?");
    $stmt->bind_param("ssssiii", $nama_game, $provider, $kategori, $gambar_thumbnail, $is_featured, $is_active, $game_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Data game berhasil diperbarui!";
        header("Location: manage_games.php");
        exit();
    } else {
        $message = "Error: " . $stmt->error;
        $message_type = "danger";
    }
    $stmt->close();
}

// Ambil data game saat ini dari database untuk ditampilkan di form
$stmt = $conn->prepare("SELECT * FROM games WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 1) {
    $game = $result->fetch_assoc();
} else {
    $_SESSION['error_message'] = "Game tidak ditemukan.";
    header("Location: manage_games.php");
    exit();
}
$stmt->close();

// Ambil daftar provider untuk dropdown
$providers = $conn->query("SELECT nama_provider FROM providers ORDER BY nama_provider ASC");
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $message_type; ?>" role="alert">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">Formulir Ubah Data Game</div>
    <div class="card-body">
        <form action="edit_game.php?id=<?php echo $game_id; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_image" value="<?php echo htmlspecialchars($game['gambar_thumbnail']); ?>">

            <div class="mb-3">
                <label for="nama_game" class="form-label">Nama Game</label>
                <input type="text" class="form-control" id="nama_game" name="nama_game" value="<?php echo htmlspecialchars($game['nama_game']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="provider" class="form-label">Provider</label>
                <select class="form-select" id="provider" name="provider" required>
                    <?php while ($p = $providers->fetch_assoc()): ?>
                        <option value="<?php echo $p['nama_provider']; ?>" <?php if ($game['provider'] == $p['nama_provider']) {
                            echo 'selected';
                        } ?>>
                            <?php echo htmlspecialchars($p['nama_provider']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="Slot" <?php if ($game['kategori'] == 'Slot') {
                        echo 'selected';
                    } ?>>Slot</option>
                    <option value="Live Casino" <?php if ($game['kategori'] == 'Live Casino') {
                        echo 'selected';
                    } ?>>Live Casino</option>
                    <option value="Sports" <?php if ($game['kategori'] == 'Sports') {
                        echo 'selected';
                    } ?>>Sports</option>
                    <option value="Togel" <?php if ($game['kategori'] == 'Togel') {
                        echo 'selected';
                    } ?>>Togel</option>
                    <option value="Fishing" <?php if ($game['kategori'] == 'Fishing') {
                        echo 'selected';
                    } ?>>Fishing</option>
                    <option value="Arcade" <?php if ($game['kategori'] == 'Arcade') {
                        echo 'selected';
                    } ?>>Arcade</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label><br>
                <img src="../assets/images/games/<?php echo htmlspecialchars($game['gambar_thumbnail']); ?>" alt="Gambar saat ini" width="150" class="mb-2 img-thumbnail">
            </div>

            <div class="mb-3">
                <label for="gambar_thumbnail" class="form-label">Ganti Gambar Thumbnail (Opsional)</label>
                <input class="form-control" type="file" id="gambar_thumbnail" name="gambar_thumbnail">
                <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?php if ($game['is_featured'] == 1) {
                    echo 'checked';
                } ?>>
                <label class="form-check-label" for="is_featured">Tampilkan sebagai Game Unggulan (Featured)?</label>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?php if ($game['is_active'] == 1) {
                    echo 'checked';
                } ?>>
                <label class="form-check-label" for="is_active">Aktifkan Game (Tampilkan di situs)?</label>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="manage_games.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>