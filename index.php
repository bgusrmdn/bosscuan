<?php
// File: hokiraja/index.php (REVISI FINAL FULL - Bersih dari Duplikasi & Header Dinamis)
require_once 'includes/header.php'; // Ini akan memproses redirect jika sudah login dan memanggil header navigasi yang sesuai

// Jika user sudah login, dia sudah direject oleh includes/header.php ke beranda.php.
// Jadi, kode di bawah ini hanya untuk user yang BELUM login.

$categories_result = $conn->query("SELECT * FROM togel_results WHERE is_active = 1 ORDER BY result_date DESC, id DESC LIMIT 3"); // Query ini seharusnya untuk togel_results, bukan categories
$categories_query_result = $conn->query("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, id ASC"); // Query yang benar untuk categories
?>

<main class="container my-4">
    <div class="row g-2 mb-4">
        <?php if ($categories_result && $categories_result->num_rows > 0): while ($togel = $categories_result->fetch_assoc()): ?>
                <div class="col-4">
                    <div class="togel-card">
                        <div class="togel-market"><?php echo htmlspecialchars($togel['market_name']); ?></div>
                        <div class="togel-date"><?php echo date('d/m/Y', strtotime($togel['result_date'])); ?></div>
                        <div class="togel-number">
                            <?php foreach (str_split($togel['result_number']) as $char): ?>
                                <span><?php echo $char; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
        <?php endwhile;
        endif; ?>
    </div>

    <div class="d-grid gap-2 d-lg-none mb-4">
        <a href="login.php" class="btn btn-outline-warning">Login</a>
        <a href="daftar.php" class="btn btn-warning fw-bold">Daftar</a>
    </div>

    <div class="game-content-desktop d-none d-lg-grid">
        <div class="category-menu-desktop" id="category-menu-desktop">
            <div class="menu-item-img active" data-filter="all">
                <i class="fas fa-star fa-fw"></i>
                <span>Semua</span>
            </div>
            <?php if ($categories_query_result && $categories_query_result->num_rows > 0): mysqli_data_seek($categories_query_result, 0);
                while ($cat = $categories_query_result->fetch_assoc()): ?>
                    <div class="menu-item-img" data-filter="<?php echo htmlspecialchars($cat['name']); ?>">
                        <i class="<?php echo htmlspecialchars($cat['icon_class']); ?> fa-fw"></i>
                        <span><?php echo htmlspecialchars($cat['name']); ?></span>
                    </div>
            <?php endwhile;
            endif; ?>
        </div>
        <div class="provider-grid-desktop" id="provider-list-container-desktop">
        </div>
    </div>

    <div class="main-game-content-mobile d-lg-none">
        <div class="game-menu-container mt-4">
            <div class="row g-0 h-100">
                <div class="col-5">
                    <div class="menu-column category-column" id="category-menu-mobile">
                        <div class="menu-item-img active" data-filter="all">
                            <i class="fas fa-star fa-2x"></i>
                            <span class="category-label">Semua</span>
                        </div>
                        <?php if ($categories_query_result && $categories_query_result->num_rows > 0): mysqli_data_seek($categories_query_result, 0);
                            while ($cat = $categories_query_result->fetch_assoc()): ?>
                                <div class="menu-item-img" data-filter="<?php echo htmlspecialchars($cat['name']); ?>">
                                    <img src="assets/images/categories/<?php echo htmlspecialchars($cat['image']); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                                    <span class="category-label"><?php echo htmlspecialchars($cat['name']); ?></span>
                                </div>
                        <?php endwhile;
                        endif; ?>
                    </div>
                </div>
                <div class="col-7">
                    <div class="menu-column provider-logo-column" id="provider-list-container-mobile">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 d-none d-lg-block">
        <div class="contact-info-sidebar mt-4">
            <h3 class="sidebar-title">HUBUNGI KAMI</h3>
            <ul class="list-unstyled">
                <li><i class="fab fa-whatsapp me-2"></i> WhatsApp: +62-812-3456-7890</li>
                <li><i class="fab fa-telegram-plane me-2"></i> Telegram: @hokiraja_support</li>
                <li><i class="fas fa-envelope me-2"></i> Email: support@hokiraja.com</li>
                <li><i class="fas fa-phone-alt me-2"></i> Telepon: (021) 12345678</li>
            </ul>
            <a href="livechat.php" class="btn btn-warning w-100 mt-3"><i class="fas fa-comments me-2"></i>Live Chat</a>
        </div>
    </div>
</main>

<div class="toast-notification" id="login-toast">
    <span id="toast-message"></span>
</div>

<?php
require_once 'includes/footer.php';
// Penting: HAPUS $conn->close() di sini karena sudah ada di includes/footer.php
// if (isset($conn)) {
//     $conn->close();
// }
?>