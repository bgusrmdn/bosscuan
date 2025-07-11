<?php
// File: Hokiraja/bantuan.php
$page_title = "Bantuan";
require_once 'includes/header.php';
?>

<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card bg-dark text-white border-secondary">
                <div class="card-body p-3 p-md-4">
                    <h3 class="text-center fw-bold mb-4">PUSAT BANTUAN</h3>

                    <div class="nav nav-pills help-nav justify-content-center mb-4" id="help-tabs">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-deposit">Deposit</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-withdraw">Tarik Dana</button>
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-password">Merubah Password</button>
                    </div>

                    <div class="tab-content" id="help-tabs-content">

                        <div class="tab-pane fade show active" id="tab-deposit" role="tabpanel">
                            <div class="accordion accordion-flush" id="faq-deposit">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-deposit-1">
                                            Bagaimana cara melakukan Deposit?
                                        </button>
                                    </h2>
                                    <div id="faq-deposit-1" class="accordion-collapse collapse" data-bs-parent="#faq-deposit">
                                        <div class="accordion-body">
                                            Untuk melakukan deposit, silakan ikuti langkah-langkah berikut:
                                            <ol>
                                                <li>Transfer ke rekening tujuan yang bisa Anda dapatkan dari menu <strong>Deposit</strong>.</li>
                                                <li>Setelah transfer berhasil, buka kembali menu <strong>Deposit</strong>.</li>
                                                <li>Isi jumlah sesuai nominal yang Anda transfer.</li>
                                                <li>Pilih tujuan sesuai bank yang Anda transfer.</li>
                                                <li>Klik tombol "Kirim" dan tunggu beberapa saat hingga deposit Anda diproses oleh admin.</li>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-deposit-2">
                                            Berapa lama proses deposit?
                                        </button>
                                    </h2>
                                    <div id="faq-deposit-2" class="accordion-collapse collapse" data-bs-parent="#faq-deposit">
                                        <div class="accordion-body">
                                            Proses deposit biasanya memakan waktu 1-3 menit setelah Anda mengisi formulir, asalkan bank yang bersangkutan sedang online dan tidak ada gangguan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-withdraw" role="tabpanel">
                            <div class="accordion accordion-flush" id="faq-withdraw">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-withdraw-1">
                                            Bagaimana cara melakukan Tarik Dana (Withdraw)?
                                        </button>
                                    </h2>
                                    <div id="faq-withdraw-1" class="accordion-collapse collapse" data-bs-parent="#faq-withdraw">
                                        <div class="accordion-body">
                                            Untuk melakukan withdraw, pastikan rekening bank utama Anda sudah benar. Buka menu <strong>Withdraw</strong>, masukkan jumlah yang ingin ditarik, masukkan password Anda untuk konfirmasi, lalu klik "Kirim Permintaan".
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab-password" role="tabpanel">
                            <div class="accordion accordion-flush" id="faq-password">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-password-1">
                                            Bagaimana cara mengubah password?
                                        </button>
                                    </h2>
                                    <div id="faq-password-1" class="accordion-collapse collapse" data-bs-parent="#faq-password">
                                        <div class="accordion-body">
                                            Buka halaman <strong>Profil</strong> Anda, lalu isi formulir "Tukar Password" dengan memasukkan password lama, password baru, dan 4 digit terakhir nomor rekening Anda untuk verifikasi keamanan.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once 'includes/footer.php';
$conn->close();
?>