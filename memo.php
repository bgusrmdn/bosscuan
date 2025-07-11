<?php
// File: Hokiraja/memo.php
$page_title = "Memo";
require_once 'includes/header.php';
?>

<main class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card bg-dark text-white border-secondary memo-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Memo</h4>
                    <i class="fas fa-envelope-open-text fa-2x text-warning"></i>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div class="memo-nav">
                        <button class="btn btn-memo-nav active" data-box="inbox"><i class="fas fa-inbox me-2"></i>Inbox</button>
                        <button class="btn btn-memo-nav" data-box="sent"><i class="fas fa-paper-plane me-2"></i>Sent</button>
                        <button class="btn btn-memo-nav" data-box="compose"><i class="fas fa-pencil-alt me-2"></i>Compose</button>
                    </div>

                    <div class="memo-content-area mt-3">
                        <div id="memo-list-view">
                            <div class="memo-toolbar">
                                <div>
                                    <input type="checkbox" id="select-all-memos" class="form-check-input">
                                    <button class="btn btn-sm btn-memo-action" id="mark-read-btn"><i class="fas fa-check-double"></i> Tandai Dibaca</button>
                                    <button class="btn btn-sm btn-memo-action" id="delete-btn"><i class="fas fa-trash"></i> Hapus</button>
                                </div>
                            </div>
                            <div class="list-group memo-list-group" id="memo-list-container">
                            </div>
                        </div>

                        <div id="memo-compose-view" class="d-none p-3">
                            <form id="compose-form">
                                <div class="mb-3">
                                    <label for="recipient" class="form-label">Kepada</label>
                                    <input type="text" class="form-control" id="recipient" value="Admin" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subjek</label>
                                    <input type="text" class="form-control" id="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="body" class="form-label">Pesan</label>
                                    <textarea class="form-control" id="body" rows="6" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning">Kirim Memo</button>
                            </form>
                        </div>

                        <div id="memo-read-view" class="d-none p-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="assets/js/memo_script.js?v=<?php echo time(); ?>"></script>

<?php
require_once 'includes/footer.php';
$conn->close();
?>