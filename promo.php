<?php
// File: hokiraja/promo.php

require_once 'includes/header.php';

// Ambil semua data promo yang aktif dari database
$promos = $conn->query("SELECT * FROM promotions WHERE is_active = 1 ORDER BY sort_order ASC");
?>

<main class="container my-4">
    <div class="page-header text-center mb-4">
        <h1 class="page-title">Promosi Spesial</h1>
        <p class="text-white-50">Temukan semua penawaran dan bonus menarik yang tersedia untuk Anda.</p>
    </div>

    <!-- Galeri Promo -->
    <div class="row g-4">
        <?php if ($promos && $promos->num_rows > 0): ?>
            <?php while ($promo = $promos->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="promo-card">
                        <div class="promo-card-image">
                            <img src="assets/images/promos/<?php echo htmlspecialchars($promo['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($promo['title']); ?>"
                                onerror="this.src='https://placehold.co/600x300/111/FFF?text=<?php echo urlencode($promo['title']); ?>';">
                        </div>
                        <div class="promo-card-body">
                            <h3 class="promo-card-title"><?php echo htmlspecialchars($promo['title']); ?></h3>
                            <p class="promo-card-description">
                                <?php echo nl2br(htmlspecialchars($promo['description'])); ?>
                            </p>
                        </div>
                        <?php if (!empty($promo['link_url']) && $promo['link_url'] !== '#'): ?>
                            <div class="promo-card-footer">
                                <a href="<?php echo htmlspecialchars($promo['link_url']); ?>" class="btn btn-warning w-100">Klaim Sekarang</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-white-50">Saat ini belum ada promosi yang tersedia. Silakan kembali lagi nanti.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
require_once 'includes/footer.php';
// Penting: tutup koneksi di akhir halaman
if (isset($conn)) {
    $conn->close();
}
?>