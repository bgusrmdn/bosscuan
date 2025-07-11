<?php
// File: admin/add_bulk_games.php (VERSI FINAL DENGAN DROPDOWN DINAMIS)

$page_title = "Tambah Game Massal (Bulk)";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$message = '';
$message_type = '';

// Cek jika form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data umum dari form
    $provider_id = $_POST['provider'];
    $kategori = $_POST['kategori'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // Ambil nama provider dari database berdasarkan ID
    $provider_stmt = $conn->prepare("SELECT nama_provider FROM providers WHERE id = ?");
    $provider_stmt->bind_param("i", $provider_id);
    $provider_stmt->execute();
    $provider_result = $provider_stmt->get_result();
    $provider_data = $provider_result->fetch_assoc();
    $provider_name = $provider_data['nama_provider'];
    $provider_stmt->close();

    $uploaded_count = 0;
    $error_count = 0;

    // --- LOGIKA UPLOAD BANYAK GAMBAR ---
    if (isset($_FILES['gambar_games']) && !empty(array_filter($_FILES['gambar_games']['name']))) {

        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/games/';

        if (!is_dir($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                $_SESSION['error_message'] = "Error: Gagal membuat folder di '{$target_dir}'. Silakan buat manual.";
                header("Location: manage_games.php");
                exit();
            }
        }

        // Loop untuk setiap file yang di-upload
        foreach ($_FILES['gambar_games']['name'] as $key => $val) {
            if ($_FILES['gambar_games']['error'][$key] == 0) {
                $original_filename = basename($_FILES["gambar_games"]["name"][$key]);

                $nama_game = pathinfo($original_filename, PATHINFO_FILENAME);
                $nama_game = str_replace(['_', '-'], ' ', $nama_game);
                $nama_game = ucwords($nama_game);

                $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
                $unique_filename = uniqid() . '_' . time() . '_' . $key . '.' . $imageFileType;
                $target_file = $target_dir . $unique_filename;

                $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];
                if (in_array($imageFileType, $allowed_types)) {
                    if (move_uploaded_file($_FILES["gambar_games"]["tmp_name"][$key], $target_file)) {
                        $stmt = $conn->prepare("INSERT INTO games (nama_game, provider, kategori, gambar_thumbnail, is_featured, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssii", $nama_game, $provider_name, $kategori, $unique_filename, $is_featured, $is_active);
                        if ($stmt->execute()) {
                            $uploaded_count++;
                        }
                        $stmt->close();
                    } else {
                        $error_count++;
                    }
                } else {
                    $error_count++;
                }
            }
        }

        if ($uploaded_count > 0) {
            $_SESSION['success_message'] = "$uploaded_count game baru berhasil ditambahkan!";
        }
        if ($error_count > 0) {
            $_SESSION['error_message'] = "$error_count file gagal diunggah.";
        }

        header("Location: manage_games.php?tab=games");
        exit();
    } else {
        $message = "Silakan pilih setidaknya satu file gambar untuk diunggah.";
        $message_type = "danger";
    }
}

// ========================================================
// === PERUBAHAN UTAMA: AMBIL DATA PROVIDER & KATEGORI ===
// ========================================================
$providers = $conn->query("SELECT * FROM providers ORDER BY nama_provider ASC");
$categories = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY name ASC");
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>
<p class="text-muted">Fitur ini memungkinkan Anda menambah banyak game sekaligus untuk satu provider dan kategori yang sama.</p>

<?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $message_type; ?>" role="alert">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">Formulir Tambah Game Massal</div>
    <div class="card-body">
        <form action="add_bulk_games.php" method="post" enctype="multipart/form-data">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="provider" class="form-label">Provider</label>
                    <select class="form-select" id="provider" name="provider" required>
                        <option value="">-- Pilih Provider --</option>
                        <?php if ($providers && $providers->num_rows > 0): while ($p = $providers->fetch_assoc()): ?>
                                <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['nama_provider']); ?></option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kategori" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php if ($categories && $categories->num_rows > 0): while ($cat = $categories->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($cat['name']); ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endwhile;
                        endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="gambar_games" class="form-label">Pilih Gambar Game (Bisa Pilih Banyak)</label>
                <input class="form-control" type="file" id="gambar_games" name="gambar_games[]" multiple required>
                <div class="form-text">Nama game akan otomatis diambil dari nama file gambar. Contoh: "Gates of Olympus.png" akan menjadi "Gates of Olympus".</div>
            </div>

            <hr>
            <p class="fw-bold">Pengaturan Umum untuk Semua Game yang Diupload:</p>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1">
                <label class="form-check-label" for="is_featured">Tampilkan sebagai Game Unggulan (Featured)?</label>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Aktifkan Game (Tampilkan di situs)?</label>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-2"></i>Upload & Simpan Semua</button>
            <a href="manage_games.php?tab=games" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>