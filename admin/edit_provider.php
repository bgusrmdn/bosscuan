<?php
// File: admin/edit_provider.php
$page_title = "Ubah Provider";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if (!isset($_GET['id'])) {
    header("Location: manage_games.php?tab=providers");
    exit();
}
$provider_id = (int)$_GET['id'];

// Logika update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_provider = $_POST['nama_provider'];
    $sort_order = (int)$_POST['sort_order'];
    $old_logo = $_POST['old_logo'];
    $logo_provider = $old_logo;

    if (isset($_FILES['logo_provider']) && $_FILES['logo_provider']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/providers/';
        $original_filename = basename($_FILES["logo_provider"]["name"]);
        $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];
        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["logo_provider"]["tmp_name"], $target_file)) {
            $logo_provider = $unique_filename;
            if (!empty($old_logo) && file_exists($target_dir . $old_logo)) {
                unlink($target_dir . $old_logo);
            }
        }
    }

    $stmt = $conn->prepare("UPDATE providers SET nama_provider = ?, logo_provider = ?, sort_order = ? WHERE id = ?");
    $stmt->bind_param("ssii", $nama_provider, $logo_provider, $sort_order, $provider_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Provider berhasil diperbarui!";
        header("Location: manage_games.php?tab=providers");
        exit();
    }
    $stmt->close();
}

// Ambil data provider saat ini
$stmt = $conn->prepare("SELECT * FROM providers WHERE id = ?");
$stmt->bind_param("i", $provider_id);
$stmt->execute();
$result = $stmt->get_result();
$provider = $result->fetch_assoc();
if (!$provider) {
    header("Location: manage_games.php?tab=providers");
    exit();
}
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>
<div class="card">
    <div class="card-header">Formulir Ubah Provider</div>
    <div class="card-body">
        <form action="edit_provider.php?id=<?php echo $provider_id; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="old_logo" value="<?php echo htmlspecialchars($provider['logo_provider']); ?>">
            <div class="mb-3">
                <label for="nama_provider" class="form-label">Nama Provider</label>
                <input type="text" class="form-control" id="nama_provider" name="nama_provider" value="<?php echo htmlspecialchars($provider['nama_provider']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Logo Saat Ini</label><br>
                <img src="../assets/images/providers/<?php echo htmlspecialchars($provider['logo_provider']); ?>" alt="Logo saat ini" height="40" class="img-thumbnail bg-dark border-dark">
            </div>
            <div class="mb-3">
                <label for="logo_provider" class="form-label">Ganti Logo (Opsional)</label>
                <input class="form-control" type="file" id="logo_provider" name="logo_provider">
            </div>
            <div class="mb-3">
                <label for="sort_order" class="form-label">Nomor Urut</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?php echo $provider['sort_order']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="manage_games.php?tab=providers" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>