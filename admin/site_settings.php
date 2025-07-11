<?php
// File: admin/site_settings.php (REVISI FINAL: Pengaturan Gambar QRIS di admin_deposit_accounts)
$page_title = "Pengaturan Situs";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

// Path upload gambar untuk logo umum (main_logo, admin_logo, footer_logo)
$upload_dir_general = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/';
// Path upload gambar untuk QRIS
$upload_dir_qris = $_SERVER['DOCUMENT_ROOT'] . '/hokiraja/assets/images/qris/';

// Pastikan folder qris ada
if (!is_dir($upload_dir_qris)) {
    mkdir($upload_dir_qris, 0777, true);
}


// Logika untuk menyimpan perubahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Loop melalui semua data POST untuk menyimpan pengaturan teks (site_settings)
    foreach ($_POST as $key => $value) {
        // Abaikan old_logos, qris_old_image, dan qris_target_method_code
        if ($key !== 'old_logos' && $key !== 'qris_old_image' && $key !== 'qris_target_method_code') {
            $stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_name = ?");
            $stmt->bind_param("ss", $value, $key);
            $stmt->execute();
            $stmt->close();
        }
    }

    // --- LOGIKA UNTUK MENYIMPAN LOGO UMUM (main_logo, admin_logo, footer_logo) ---
    $old_logos = $_POST['old_logos'] ?? []; // Pastikan ini terdefinisi
    foreach (['main_logo', 'admin_logo', 'footer_logo'] as $logo_type) {
        if (isset($_FILES[$logo_type]) && $_FILES[$logo_type]['error'] == 0) {
            $original_filename = basename($_FILES[$logo_type]["name"]);
            $imageFileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
            $unique_filename = $logo_type . '_' . time() . '.' . $imageFileType;
            $target_file = $upload_dir_general . $unique_filename;
            $allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];

            if (in_array($imageFileType, $allowed_types) && move_uploaded_file($_FILES[$logo_type]["tmp_name"], $target_file)) {
                // Update nama file baru di database site_settings
                $stmt = $conn->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_name = ?");
                $stmt->bind_param("ss", $unique_filename, $logo_type);
                $stmt->execute();
                $stmt->close();

                // Hapus logo lama jika ada
                $old_logo_file = $old_logos[$logo_type] ?? '';
                if (!empty($old_logo_file) && file_exists($upload_dir_general . $old_logo_file)) {
                    unlink($upload_dir_general . $old_logo_file);
                }
            } else {
                $_SESSION['error_message'] = "Gagal mengunggah logo $logo_type atau tipe file tidak diizinkan.";
            }
        }
    }

    // --- LOGIKA BARU UNTUK MENYIMPAN/MENGHAPUS GAMBAR QRIS di admin_deposit_accounts ---
    $qris_target_method_code = $_POST['qris_target_method_code'] ?? 'QRIS_STATIC'; // Ambil dari hidden input atau default
    $qris_old_image = $_POST['qris_old_image'] ?? ''; // Ambil nama file lama dari hidden input

    if (isset($_FILES['qris_image']) && $_FILES['qris_image']['error'] == 0) { // Jika ada file baru diupload
        $qris_original_filename = basename($_FILES['qris_image']['name']);
        $qris_imageFileType = strtolower(pathinfo($qris_original_filename, PATHINFO_EXTENSION));
        $qris_unique_filename = 'qris_' . time() . '.' . $qris_imageFileType;
        $qris_target_file = $upload_dir_qris . $qris_unique_filename;
        $qris_allowed_types = ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'];

        if (in_array($qris_imageFileType, $qris_allowed_types) && move_uploaded_file($_FILES['qris_image']['tmp_name'], $qris_target_file)) {
            // Update nama file baru di database admin_deposit_accounts
            $stmt = $conn->prepare("UPDATE admin_deposit_accounts SET qris_image_url = ? WHERE method_code = ?");
            $stmt->bind_param("ss", $qris_unique_filename, $qris_target_method_code);
            if ($stmt->execute()) {
                // Hapus gambar QRIS lama jika ada
                if (!empty($qris_old_image) && file_exists($upload_dir_qris . $qris_old_image)) {
                    unlink($upload_dir_qris . $qris_old_image);
                }
            } else {
                $_SESSION['error_message'] = "Gagal update gambar QRIS di database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Gagal mengunggah file QRIS atau tipe file tidak diizinkan.";
        }
    } elseif (isset($_POST['delete_qris_image']) && $_POST['delete_qris_image'] === '1') { // Jika tombol hapus diklik
        // Hapus dari database (set menjadi NULL)
        $stmt = $conn->prepare("UPDATE admin_deposit_accounts SET qris_image_url = NULL WHERE method_code = ?");
        $stmt->bind_param("s", $qris_target_method_code);
        if ($stmt->execute()) {
            // Hapus file fisik
            if (!empty($qris_old_image) && file_exists($upload_dir_qris . $qris_old_image)) {
                unlink($upload_dir_qris . $qris_old_image);
            }
            $_SESSION['success_message'] = "Gambar QRIS berhasil dihapus!";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus gambar QRIS dari database: " . $stmt->error;
        }
        $stmt->close();
    }


    $_SESSION['success_message'] = ($_SESSION['success_message'] ?? "") . " Pengaturan situs berhasil diperbarui!";
    // Redirect untuk mencegah resubmit form
    header("Location: site_settings.php");
    exit();
}

// Ambil semua pengaturan dari database site_settings untuk ditampilkan di form
$settings_result = $conn->query("SELECT * FROM site_settings");
$settings = [];
while ($row = $settings_result->fetch_assoc()) {
    $settings[$row['setting_name']] = $row['setting_value'];
}

// === BARU: Ambil URL Gambar QRIS dari admin_deposit_accounts untuk ditampilkan ===
$qris_target_method_code = 'QRIS_STATIC'; // <-- PENTING: GANTI dengan method_code QRIS Anda
$qris_image_data = $conn->prepare("SELECT qris_image_url FROM admin_deposit_accounts WHERE method_code = ?");
$qris_image_data->bind_param("s", $qris_target_method_code);
$qris_image_data->execute();
$qris_image_result = $qris_image_data->get_result();
$qris_current_image_url = '';
if ($qris_image_result->num_rows > 0) {
    $qris_current_image_url = $qris_image_result->fetch_assoc()['qris_image_url'];
}
$qris_image_data->close();

// --- LOGIKA UNTUK MENYIMPAN LOGO UMUM ---
$old_logos = $_POST['old_logos'] ?? [];
// TAMBAHKAN 'admin_profile_picture' KE DALAM ARRAY INI
foreach (['main_logo', 'admin_logo', 'footer_logo', 'admin_profile_picture'] as $logo_type) {
}
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
    unset($_SESSION['error_message']);
}
?>

<form action="site_settings.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="qris_target_method_code" value="<?php echo htmlspecialchars($qris_target_method_code); ?>">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Pengaturan Teks & Konten</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="site_title" class="form-label">Judul Situs (Title Tag)</label>
                        <input type="text" class="form-control" id="site_title" name="site_title" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="footer_tagline" class="form-label">Teks Tagline Footer</label>
                        <textarea class="form-control" id="footer_tagline" name="footer_tagline" rows="2"><?php echo htmlspecialchars($settings['footer_tagline'] ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Pengaturan Logo & QRIS</div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Logo Utama (Header)</label><br>
                        <img src="../assets/images/<?php echo htmlspecialchars($settings['main_logo'] ?? 'default_logo.png'); ?>" height="40" class="img-thumbnail bg-dark border-dark mb-2">
                        <input type="hidden" name="old_logos[main_logo]" value="<?php echo htmlspecialchars($settings['main_logo'] ?? ''); ?>">
                        <input class="form-control" type="file" name="main_logo" accept="image/*">
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Logo Admin (Sidebar)</label><br>
                        <img src="../assets/images/<?php echo htmlspecialchars($settings['admin_logo'] ?? 'default_admin_logo.png'); ?>" height="40" class="img-thumbnail bg-dark border-dark mb-2">
                        <input type="hidden" name="old_logos[admin_logo]" value="<?php echo htmlspecialchars($settings['admin_logo'] ?? ''); ?>">
                        <input class="form-control" type="file" name="admin_logo" accept="image/*">
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Logo Footer (Mobile)</label><br>
                        <img src="../assets/images/<?php echo htmlspecialchars($settings['footer_logo'] ?? 'default_footer_logo.png'); ?>" height="40" class="img-thumbnail bg-dark border-dark mb-2">
                        <input type="hidden" name="old_logos[footer_logo]" value="<?php echo htmlspecialchars($settings['footer_logo'] ?? ''); ?>">
                        <input class="form-control" type="file" name="footer_logo" accept="image/*">
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Foto Profil Admin (Live Chat)</label><br>
                        <img src="../assets/images/<?php echo htmlspecialchars($settings['admin_profile_picture'] ?? 'default_admin_profile.png'); ?>" height="40" class="img-thumbnail bg-dark border-dark mb-2 rounded-circle">
                        <input type="hidden" name="old_logos[admin_profile_picture]" value="<?php echo htmlspecialchars($settings['admin_profile_picture'] ?? ''); ?>">
                        <input class="form-control" type="file" name="admin_profile_picture" accept="image/*">
                        <small class="form-text text-muted">Disarankan gambar persegi (1:1).</small>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Gambar QRIS (Untuk Deposit)</label><br>
                        <?php if (!empty($qris_current_image_url)): ?>
                            <img src="../assets/images/qris/<?php echo htmlspecialchars($qris_current_image_url); ?>" height="100" class="img-thumbnail bg-dark border-dark mb-2">
                            <div class="form-check form-check-inline ms-2">
                                <input class="form-check-input" type="checkbox" id="delete_qris_image" name="delete_qris_image" value="1">
                                <label class="form-check-label text-danger" for="delete_qris_image">Hapus Gambar Ini</label>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">Gambar QRIS belum diatur.</p>
                        <?php endif; ?>
                        <input type="hidden" name="qris_old_image" value="<?php echo htmlspecialchars($qris_current_image_url); ?>">
                        <input class="form-control mt-2" type="file" name="qris_image" accept="image/*">
                        <small class="form-text text-muted">Upload gambar QRIS Anda (JPG, PNG, WebP, dll.). Disarankan ukuran persegi.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-primary w-100">Simpan Semua Pengaturan</button>
    </div>
</form>

<?php
$conn->close();
require_once 'includes/footer.php';
?>