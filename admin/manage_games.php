<?php
// File: admin/manage_games.php (VERSI FINAL DENGAN URUTAN)
$page_title = "Manajemen Game";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'games';
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
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link <?php echo ($active_tab == 'games') ? 'active' : ''; ?>" href="manage_games.php?tab=games">Daftar Game</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($active_tab == 'providers') ? 'active' : ''; ?>" href="manage_games.php?tab=providers">Daftar Provider</a></li>
    <li class="nav-item"><a class="nav-link <?php echo ($active_tab == 'categories') ? 'active' : ''; ?>" href="manage_games.php?tab=categories">Daftar Kategori</a></li>
</ul>
<div class="tab-content">

    <!-- KONTEN TAB 1: DAFTAR GAME -->
    <?php if ($active_tab == 'games'): ?>
        <div class="tab-pane fade show active">
            <div class="mb-3"><a href="add_bulk_games.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Game Massal</a></div>
            <div class="card">
                <div class="card-header">Daftar Semua Game</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Nama Game</th>
                                    <th>Provider</th>
                                    <th>Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $games_result = $conn->query("SELECT * FROM games ORDER BY id DESC");
                                if ($games_result && $games_result->num_rows > 0):
                                    while ($row = $games_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><img src="../assets/images/games/<?php echo htmlspecialchars($row['gambar_thumbnail']); ?>" width="80" onerror="this.src='https://placehold.co/80x80/EEE/31343C?text=No+Image';"></td>
                                            <td><?php echo htmlspecialchars($row['nama_game']); ?></td>
                                            <td><?php echo htmlspecialchars($row['provider']); ?></td>
                                            <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                            <td><span class="badge bg-<?php echo ($row['is_active'] == 1) ? 'success' : 'danger'; ?>"><?php echo ($row['is_active'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></span></td>
                                            <td>
                                                <a href="edit_game.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Ubah"><i class="fas fa-edit"></i></a>
                                                <a href="delete_game.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus game ini?');"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile;
                                else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data game.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- KONTEN TAB 2: DAFTAR PROVIDER -->
    <?php if ($active_tab == 'providers'): ?>
        <div class="tab-pane fade show active">
            <div class="mb-3"><a href="add_provider.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Provider Baru</a></div>
            <div class="card">
                <div class="card-header">Daftar Semua Provider</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Logo</th>
                                    <th>Nama Provider</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $providers_result = $conn->query("SELECT * FROM providers ORDER BY sort_order ASC, id ASC");
                                if ($providers_result && $providers_result->num_rows > 0):
                                    while ($row = $providers_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['sort_order']; ?></td>
                                            <td><img src="../assets/images/providers/<?php echo htmlspecialchars($row['logo_provider']); ?>" width="100" onerror="this.src='https://placehold.co/100x40/EEE/31343C?text=No+Logo';"></td>
                                            <td><?php echo htmlspecialchars($row['nama_provider']); ?></td>
                                            <td>
                                                <a href="edit_provider.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Ubah"><i class="fas fa-edit"></i></a>
                                                <a href="delete_provider.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus provider ini?');"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- KONTEN TAB 3: DAFTAR KATEGORI -->
    <?php if ($active_tab == 'categories'): ?>
        <div class="tab-pane fade show active">
            <div class="mb-3"><a href="add_category.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah Kategori Baru</a></div>
            <div class="card">
                <div class="card-header">Daftar Semua Kategori Game</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Urutan</th>
                                    <th>Gambar</th>
                                    <th>Nama Kategori</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $categories_result = $conn->query("SELECT * FROM categories ORDER BY sort_order ASC, id ASC");
                                if ($categories_result && $categories_result->num_rows > 0):
                                    while ($row = $categories_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $row['sort_order']; ?></td>
                                            <td><img src="../assets/images/categories/<?php echo htmlspecialchars($row['image']); ?>" width="80" onerror="this.src='https://placehold.co/80x80/EEE/31343C?text=No+Image';"></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                                            <td><span class="badge bg-<?php echo ($row['is_active'] == 1) ? 'success' : 'danger'; ?>"><?php echo ($row['is_active'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></span></td>
                                            <td>
                                                <a href="edit_category.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning" title="Ubah"><i class="fas fa-edit"></i></a>
                                                <a href="delete_category.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus kategori ini?');"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $conn->close();
require_once 'includes/footer.php'; ?>