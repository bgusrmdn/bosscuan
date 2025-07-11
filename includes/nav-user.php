<?php
// File: includes/nav-user.php (REVISI FINAL FULL - Tombol Toggle Sidebar Mobile)
// Ambil data saldo user dari database
$user_id_from_session = $_SESSION['user_id'];
$balance_stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$balance_stmt->bind_param("i", $user_id_from_session);
$balance_stmt->execute();
$balance_result = $balance_stmt->get_result();
$user_balance = ($balance_result->num_rows > 0) ? $balance_result->fetch_assoc()['balance'] : 0;
$balance_stmt->close();
?>
<header class="user-main-header sticky-top">
    <div class="super-top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="beranda.php" class="header-logo-link">
                <img src="assets/images/<?php echo htmlspecialchars($settings['main_logo'] ?? 'logo.png'); ?>" alt="Logo Situs">
            </a>

            <div class="header-actions">
                <button class="btn btn-header-icon"><i class="fas fa-bell"></i></button>
                <button class="btn btn-header-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#userSidebar" aria-controls="userSidebar">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="bottom-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="deposit.php" class="header-icon-link">
                <i class="fas fa-wallet"></i>
                <span>Deposit</span>
            </a>
            <a href="withdraw.php" class="header-icon-link">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Withdraw</span>
            </a>
            <a href="rekening.php" class="header-icon-link">
                <i class="fas fa-university"></i>
                <span>Akun Bank</span>
            </a>
            <div class="user-wallet-block ms-auto">
                <div class="user-icon-wrapper">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="user-details-wrapper">
                    <span class="username"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
                    <div class="balance" id="user-balance">
                        <span>IDR <?php echo number_format($user_balance, 0, ',', '.'); ?></span>
                    </div>
                </div>
                <button id="refresh-balance" class="btn btn-sm btn-refresh-wallet"><i class="fas fa-sync-alt"></i></button>
            </div>
        </div>
    </div>
</header>