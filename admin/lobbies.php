<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['close_lobby'])) {
        $lobby_id = intval($_POST['lobby_id']);
        mysqli_query($conn, "UPDATE lobbies SET status = 'Closed' WHERE id = $lobby_id");
        showAlert('Lobby berhasil ditutup!', 'success');
    }

    if (isset($_POST['delete_lobby'])) {
        $lobby_id = intval($_POST['lobby_id']);
        mysqli_query($conn, "DELETE FROM lobbies WHERE id = $lobby_id");
        showAlert('Lobby berhasil dihapus!', 'success');
    }

    if (isset($_POST['toggle_featured'])) {
        $lobby_id = intval($_POST['lobby_id']);
        $featured = intval($_POST['featured']);
        $new_featured = $featured ? 0 : 1;
        mysqli_query($conn, "UPDATE lobbies SET featured = $new_featured WHERE id = $lobby_id");
        showAlert('Status featured berhasil diubah!', 'success');
    }

    redirect('lobbies.php');
}

// Get filters
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? sanitize($_GET['kategori']) : '';
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Get lobbies
$query = "SELECT l.*, u.username, u.avatar FROM lobbies l
          JOIN users u ON l.user_id = u.id WHERE 1=1";
if ($search) {
    $query .= " AND l.judul_aktivitas LIKE '%$search%'";
}
if ($kategori) {
    $query .= " AND l.kategori = '$kategori'";
}
if ($status) {
    $query .= " AND l.status = '$status'";
}
$query .= " ORDER BY l.featured DESC, l.created_at DESC";

$lobbies = mysqli_query($conn, $query);
$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Lobbies - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .lobbies-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .filters input, .filters select {
            padding: 0.75rem;
            background: var(--dark-lighter);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 8px;
            color: var(--text);
        }

        .lobbies-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .admin-lobby-card {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            position: relative;
        }

        .admin-lobby-card.featured {
            border-color: var(--accent);
        }

        .lobby-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-shield-alt"></i>
                    <span>Admin Panel</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="users.php" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="lobbies.php" class="nav-item active">
                    <i class="fas fa-gamepad"></i>
                    <span>Lobbies</span>
                </a>
                <a href="activities.php" class="nav-item">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a>
                <a href="settings.php" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="../index.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>Back to Site</span>
                </a>
                <a href="../logout.php" class="nav-item">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <?php if ($alert): ?>
            <div class="alert alert-<?php echo $alert['type']; ?>">
                <?php echo $alert['message']; ?>
            </div>
            <?php endif; ?>

            <div class="lobbies-header">
                <h1>Manage Lobbies</h1>
            </div>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" style="display: flex; gap: 1rem; flex: 1; flex-wrap: wrap;">
                    <input type="text" name="search" placeholder="Search lobbies..." value="<?php echo $search; ?>" style="flex: 1; min-width: 200px;">
                    <select name="kategori">
                        <option value="">All Categories</option>
                        <option value="Game" <?php echo $kategori == 'Game' ? 'selected' : ''; ?>>Game</option>
                        <option value="Olahraga" <?php echo $kategori == 'Olahraga' ? 'selected' : ''; ?>>Olahraga</option>
                        <option value="Belajar" <?php echo $kategori == 'Belajar' ? 'selected' : ''; ?>>Belajar</option>
                        <option value="Hangout" <?php echo $kategori == 'Hangout' ? 'selected' : ''; ?>>Hangout</option>
                        <option value="Kompetisi" <?php echo $kategori == 'Kompetisi' ? 'selected' : ''; ?>>Kompetisi</option>
                    </select>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="Open" <?php echo $status == 'Open' ? 'selected' : ''; ?>>Open</option>
                        <option value="Full" <?php echo $status == 'Full' ? 'selected' : ''; ?>>Full</option>
                        <option value="Closed" <?php echo $status == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </form>
            </div>

            <!-- Lobbies Grid -->
            <div class="lobbies-grid">
                <?php while ($lobby = mysqli_fetch_assoc($lobbies)): ?>
                <div class="admin-lobby-card <?php echo $lobby['featured'] ? 'featured' : ''; ?>">
                    <?php if ($lobby['featured']): ?>
                        <div class="featured-badge">
                            <i class="fas fa-star"></i> Featured
                        </div>
                    <?php endif; ?>

                    <div class="lobby-header">
                        <div class="lobby-category category-<?php echo strtolower($lobby['kategori']); ?>">
                            <?php echo $lobby['kategori']; ?>
                        </div>
                        <div class="lobby-status status-<?php echo strtolower($lobby['status']); ?>">
                            <?php echo $lobby['status']; ?>
                        </div>
                    </div>

                    <h3 class="lobby-title"><?php echo $lobby['judul_aktivitas']; ?></h3>

                    <?php if ($lobby['game_type']): ?>
                        <div class="lobby-game">
                            <i class="fas fa-gamepad"></i> <?php echo $lobby['game_type']; ?>
                        </div>
                    <?php endif; ?>

                    <p class="lobby-description"><?php echo substr($lobby['deskripsi'], 0, 80); ?>...</p>

                    <div class="lobby-footer" style="margin-top: 1rem;">
                        <div class="lobby-host">
                            <img src="../uploads/<?php echo $lobby['avatar']; ?>" alt="<?php echo $lobby['username']; ?>" style="width: 30px; height: 30px;">
                            <span><?php echo $lobby['username']; ?></span>
                        </div>
                        <div class="lobby-players">
                            <i class="fas fa-users"></i>
                            <span><?php echo $lobby['current_players']; ?>/<?php echo $lobby['max_players']; ?></span>
                        </div>
                    </div>

                    <div class="lobby-actions">
                        <a href="../lobby-detail.php?id=<?php echo $lobby['id']; ?>" class="btn btn-sm btn-outline" target="_blank">
                            <i class="fas fa-eye"></i> View
                        </a>

                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="lobby_id" value="<?php echo $lobby['id']; ?>">
                            <input type="hidden" name="featured" value="<?php echo $lobby['featured']; ?>">
                            <button type="submit" name="toggle_featured" class="btn btn-sm" style="background: var(--accent);">
                                <i class="fas fa-star"></i>
                            </button>
                        </form>

                        <?php if ($lobby['status'] != 'Closed'): ?>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="lobby_id" value="<?php echo $lobby['id']; ?>">
                            <button type="submit" name="close_lobby" class="btn btn-sm" style="background: var(--warning);" onclick="return confirm('Tutup lobby ini?')">
                                <i class="fas fa-lock"></i>
                            </button>
                        </form>
                        <?php endif; ?>

                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="lobby_id" value="<?php echo $lobby['id']; ?>">
                            <button type="submit" name="delete_lobby" class="btn btn-sm" style="background: var(--danger);" onclick="return confirm('Hapus lobby ini?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>
</body>
</html>
