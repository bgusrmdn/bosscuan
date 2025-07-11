<?php
// File: Hokiraja/withdraw.php

$page_title = "Withdraw Dana";
require_once 'includes/header.php'; // Header akan menangani redirect jika ada withdraw pending

$user_id = $_SESSION['user_id'];

// 1. Ambil data rekening bank utama pengguna
$bank_stmt = $conn->prepare(
    "SELECT ub.bank_name, ub.account_name, ub.account_number, pm.logo 
     FROM user_banks ub
     LEFT JOIN payment_methods pm ON ub.bank_name = pm.method_code
     WHERE ub.user_id = ? AND ub.is_primary = 1"
);
$bank_stmt->bind_param("i", $user_id);
$bank_stmt->execute();
$bank_result = $bank_stmt->get_result();
$primary_bank = $bank_result->fetch_assoc();
$bank_stmt->close();

// 2. Ambil saldo pengguna saat ini
$user_stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_balance = $user_stmt->get_result()->fetch_assoc()['balance'];
$user_stmt->close();

// Logika untuk menampilkan notifikasi jika ada pesan error dari proses sebelumnya
if (isset($_SESSION['withdraw_error'])) {
    $error_message = $_SESSION['withdraw_error'];
    unset($_SESSION['withdraw_error']);
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: '" . htmlspecialchars($error_message) . "',
                icon: 'error',
                background: '#212529', color: '#fff', confirmButtonColor: '#ffc107'
            });
        });
    </script>";
}
?>

<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card bg-dark text-white border-secondary">
                <div class="card-body p-4">
                    <h1 class="page-title text-center mb-4">Formulir Penarikan Dana</h1>

                    <?php if (!$primary_bank) : ?>
                        <div class="alert alert-warning text-center">
                            Anda belum mengatur rekening bank utama. Silakan hubungi admin untuk menambahkan rekening Anda.
                        </div>
                    <?php else : ?>
                        <form action="process_withdraw.php" method="POST" id="withdraw-form">

                            <div class="mb-3">
                                <label class="form-label">Tujuan Penarikan</label>
                                <div class="card bg-secondary p-3">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-bold fs-5"><?php echo htmlspecialchars($primary_bank['bank_name']); ?></div>
                                            <div><?php echo htmlspecialchars($primary_bank['account_number']); ?> (a.n. <?php echo htmlspecialchars($primary_bank['account_name']); ?>)</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">Ini adalah rekening utama Anda. Untuk mengubahnya, silakan hubungi customer service.</div>
                            </div>

                            <hr class="border-secondary">

                            <div class="mb-3">
                                <label class="form-label">Saldo Anda Saat Ini</label>
                                <input type="text" class="form-control" value="IDR <?php echo number_format($user_balance, 0, ',', '.'); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="amount" class="form-label">Jumlah Penarikan</label>
                                <input type="text" class="form-control deposit-amount-input" id="amount" name="amount" placeholder="Contoh: 50.000" required>
                                <div class="form-text">Minimal penarikan adalah IDR 50.000.</div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Konfirmasi</label>
                                <input type="password" class="form-control" id="password" name="password" required placeholder="Masukkan password Anda untuk konfirmasi">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-warning btn-lg fw-bold">Kirim Permintaan</button>
                            </div>

                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
$conn->close();
?>