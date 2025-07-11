<?php
// File: admin/add_provider.php
$page_title = "Tambah Provider Baru";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_provider = $_POST['nama_provider'];
    $sort_order = (int)$_POST['sort_order'];
    $logo_provider = '';

    if (isset($_FILES['logo_provider']) && $_FILES['logo_provider']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/providers/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $original_filename = basename($_FILES["logo_provider"]["name"]);
        $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
        $unique_filename = uniqid() . '_' . time() . '.' . $imageFileType;
        $target_file = $target_dir . $unique_filename;

        $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];
        if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES["logo_provider"]["tmp_name"], $target_file)) {
            $logo_provider = $unique_filename;
        }
    }

    $stmt = $conn->prepare("INSERT INTO providers (nama_provider, logo_provider, sort_order) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nama_provider, $logo_provider, $sort_order);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Provider baru berhasil ditambahkan!";
        header("Location: manage_games.php?tab=providers");
        exit();
    }
    $stmt->close();
}
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>
<div class="card">
    <div class="card-header">Formulir Provider Baru</div>
    <div class="card-body">
        <form action="add_provider.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="nama_provider" class="form-label">Nama Provider</label>
                <input type="text" class="form-control" id="nama_provider" name="nama_provider" required>
            </div>
            <div class="mb-3">
                <label for="logo_provider" class="form-label">Logo Provider</label>
                <input class="form-control" type="file" id="logo_provider" name="logo_provider">
            </div>
            <div class="mb-3">
                <label for="sort_order" class="form-label">Nomor Urut</label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" value="0" required>
                <div class="form-text">Angka lebih kecil akan tampil lebih dulu.</div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Provider</button>
            <a href="manage_games.php?tab=providers" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>