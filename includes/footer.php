<?php
// File: includes/footer.php (REVISI FINAL: Tambahan SweetAlert2 JS)
?>
<nav class="mobile-footer-nav d-lg-none fixed-bottom">
    <?php
    // Dapatkan nama file saat ini untuk menyorot menu yang aktif
    $current_page = basename($_SERVER['PHP_SELF']);
    // Tentukan navigasi footer berdasarkan status login
    if (isset($_SESSION['user_id'])) {
    ?>
        <a href="beranda.php" class="nav-item <?php echo ($current_page == 'beranda.php') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i><span>Home</span>
        </a>
        <a href="transaksi.php" class="nav-item <?php echo ($current_page == 'transaksi.php') ? 'active' : ''; ?>">
            <i class="fas fa-exchange-alt"></i><span>Transaksi</span>
        </a>
        <a href="promo.php" class="nav-item <?php echo ($current_page == 'promo.php') ? 'active' : ''; ?>">
            <i class="fas fa-gift"></i><span>Promosi</span>
        </a>
        <a href="livechat.php" class="nav-item <?php echo ($current_page == 'livechat.php') ? 'active' : ''; ?>">
            <i class="fas fa-comments"></i><span>Live Chat</span>
        </a>
        <a href="profil.php" class="nav-item <?php echo ($current_page == 'profil.php') ? 'active' : ''; ?>">
            <i class="fas fa-user"></i><span>Profil</span>
        </a>
    <?php
    } else {
    ?>
        <a href="index.php" class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <i class="fas fa-home"></i><span>Home</span>
        </a>
        <a href="promo.php" class="nav-item <?php echo ($current_page == 'promo.php') ? 'active' : ''; ?>">
            <i class="fas fa-gift"></i><span>Promo</span>
        </a>
        <a href="daftar.php" class="nav-item register-btn <?php echo ($current_page == 'daftar.php') ? 'active' : ''; ?>">
            <i class="fas fa-user-plus"></i><span>Daftar</span>
        </a>
        <a href="livechat.php" class="nav-item <?php echo ($current_page == 'livechat.php') ? 'active' : ''; ?>">
            <i class="fas fa-comments"></i><span>Live Chat</span>
        </a>
        <a href="login.php" class="nav-item <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">
            <i class="fas fa-sign-in-alt"></i><span>Login</span>
        </a>
    <?php
    }
    ?>
</nav>
<footer class="main-footer text-center p-4 mt-5">
    <div class="container">
        <p class="footer-tagline mb-2"><?php echo htmlspecialchars($settings['footer_tagline'] ?? ''); ?></p>

        <p class="text-white-50">&copy; <?php echo date('Y'); ?> HOKIRAJA. All Rights Reserved.</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
<script src="assets/js/script.js?v=<?php echo time(); ?>"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</body>

</html>