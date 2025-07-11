<?php
$page_title = "Manajemen Live Chat";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<h1 class="mb-4"><?php echo $page_title; ?></h1>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-comments me-1"></i> Daftar Percakapan
            </div>
            <div class="list-group list-group-flush" id="conversation-list" style="height: 600px; overflow-y: auto;">
                <div class="p-5 text-center text-muted">Memuat percakapan...</div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header" id="chat-header">
                Pilih percakapan untuk memulai
            </div>
            <div class="chat-window-body" id="admin-chat-window">
            </div>
            <div class="card-footer">
                <form id="admin-reply-form" class="d-flex" style="display: none!important;">
                    <input type="hidden" id="active-session-id" name="session_id">
                    <input type="text" id="admin-message-input" class="form-control me-2" placeholder="Ketik balasan..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>