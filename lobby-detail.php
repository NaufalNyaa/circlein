<?php
require_once 'config.php';

$lobby_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!$lobby_id) {
    redirect('index.php');
}

// Get lobby details with host info
$query = "SELECT l.*, u.username, u.avatar, u.rank, u.rating
          FROM lobbies l
          JOIN users u ON l.user_id = u.id
          WHERE l.id = $lobby_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    showAlert('Lobby tidak ditemukan!', 'danger');
    redirect('index.php');
}

$lobby = mysqli_fetch_assoc($result);

// Update views
mysqli_query($conn, "UPDATE lobbies SET views = views + 1 WHERE id = $lobby_id");

// Get members
$members_query = "SELECT lm.*, u.username, u.avatar, u.rank
                  FROM lobby_members lm
                  JOIN users u ON lm.user_id = u.id
                  WHERE lm.lobby_id = $lobby_id AND lm.status = 'accepted'";
$members_result = mysqli_query($conn, $members_query);

$is_member = false;
$is_host = false;

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    $is_host = ($lobby['user_id'] == $user_id);

    $check_member = mysqli_query($conn, "SELECT id FROM lobby_members WHERE lobby_id = $lobby_id AND user_id = $user_id");
    $is_member = mysqli_num_rows($check_member) > 0;
}

// Handle join lobby
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['join_lobby'])) {
    if (!isLoggedIn()) {
        showAlert('Silakan login terlebih dahulu!', 'warning');
        redirect('login.php');
    }

    if ($lobby['status'] == 'Full') {
        showAlert('Lobby sudah penuh!', 'danger');
    } elseif ($is_member) {
        showAlert('Anda sudah bergabung di lobby ini!', 'warning');
    } else {
        $insert = "INSERT INTO lobby_members (lobby_id, user_id) VALUES ($lobby_id, $user_id)";
        if (mysqli_query($conn, $insert)) {
            mysqli_query($conn, "UPDATE lobbies SET current_players = current_players + 1 WHERE id = $lobby_id");

            // Check if full
            $lobby['current_players']++;
            if ($lobby['current_players'] >= $lobby['max_players']) {
                mysqli_query($conn, "UPDATE lobbies SET status = 'Full' WHERE id = $lobby_id");
            }

            logActivity($user_id, 'join_lobby', "Joined lobby: {$lobby['judul_aktivitas']}");
            showAlert('Berhasil bergabung ke lobby!', 'success');
            redirect('lobby-detail.php?id=' . $lobby_id);
        }
    }
}

// Handle leave lobby
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['leave_lobby'])) {
    if (isLoggedIn() && $is_member && !$is_host) {
        $delete = "DELETE FROM lobby_members WHERE lobby_id = $lobby_id AND user_id = $user_id";
        if (mysqli_query($conn, $delete)) {
            mysqli_query($conn, "UPDATE lobbies SET current_players = current_players - 1, status = 'Open' WHERE id = $lobby_id");
            logActivity($user_id, 'leave_lobby', "Left lobby: {$lobby['judul_aktivitas']}");
            showAlert('Anda telah keluar dari lobby!', 'success');
            redirect('index.php');
        }
    }
}

$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lobby['judul_aktivitas']; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .detail-section {
            padding: 6rem 0 3rem;
            min-height: 80vh;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 2rem;
        }

        .detail-main {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
        }

        .detail-header {
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 240, 255, 0.1);
        }

        .detail-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .detail-meta {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
        }

        .meta-item i {
            color: var(--primary);
        }

        .detail-content {
            line-height: 1.8;
            color: var(--text);
            margin-bottom: 2rem;
        }

        .detail-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .info-card {
            background: var(--dark-lighter);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid rgba(0, 240, 255, 0.1);
        }

        .info-card h4 {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .detail-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .sidebar-card {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .sidebar-card h3 {
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .host-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: var(--dark-lighter);
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .host-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid var(--primary);
        }

        .host-info h4 {
            margin-bottom: 0.25rem;
        }

        .members-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .member-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: var(--dark-lighter);
            border-radius: 8px;
        }

        .member-item img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary);
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (max-width: 1024px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="nav-brand">
                <i class="fas fa-gamepad"></i>
                <span>CircleIn</span>
            </div>
            <div class="nav-menu">
                <a href="index.php" class="nav-link">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <?php if (isLoggedIn()): ?>
                    <a href="profile.php" class="nav-link">
                        <img src="uploads/<?php echo $_SESSION['avatar']; ?>" class="nav-avatar">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if ($alert): ?>
    <div class="alert alert-<?php echo $alert['type']; ?>">
        <div class="container">
            <?php echo $alert['message']; ?>
            <button class="alert-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Detail Section -->
    <section class="detail-section">
        <div class="container">
            <div class="detail-grid">
                <!-- Main Content -->
                <div class="detail-main">
                    <div class="detail-header">
                        <div class="lobby-category category-<?php echo strtolower($lobby['kategori']); ?>" style="display: inline-flex;">
                            <?php
                            $icons = [
                                'Game' => 'gamepad',
                                'Olahraga' => 'futbol',
                                'Belajar' => 'book',
                                'Hangout' => 'coffee',
                                'Kompetisi' => 'trophy'
                            ];
                            ?>
                            <i class="fas fa-<?php echo $icons[$lobby['kategori']]; ?>"></i>
                            <?php echo $lobby['kategori']; ?>
                        </div>

                        <h1 class="detail-title"><?php echo $lobby['judul_aktivitas']; ?></h1>

                        <?php if ($lobby['game_type']): ?>
                            <div style="font-size: 1.2rem; color: var(--text-muted); margin-bottom: 1rem;">
                                <i class="fas fa-gamepad"></i> <?php echo $lobby['game_type']; ?>
                            </div>
                        <?php endif; ?>

                        <div class="detail-meta">
                            <span class="meta-item">
                                <i class="fas fa-eye"></i>
                                <?php echo $lobby['views']; ?> views
                            </span>
                            <span class="meta-item">
                                <i class="fas fa-clock"></i>
                                <?php echo timeAgo($lobby['created_at']); ?>
                            </span>
                            <span class="lobby-status status-<?php echo strtolower($lobby['status']); ?>">
                                <?php echo $lobby['status']; ?>
                            </span>
                        </div>
                    </div>

                    <div class="detail-content">
                        <h3><i class="fas fa-info-circle"></i> Deskripsi</h3>
                        <p><?php echo nl2br($lobby['deskripsi']); ?></p>
                    </div>

                    <div class="detail-info">
                        <?php if ($lobby['lokasi']): ?>
                        <div class="info-card">
                            <h4><i class="fas fa-map-marker-alt"></i> Lokasi</h4>
                            <p><?php echo $lobby['lokasi']; ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if ($lobby['waktu_kumpul']): ?>
                        <div class="info-card">
                            <h4><i class="fas fa-calendar"></i> Waktu Kumpul</h4>
                            <p><?php echo date('d F Y, H:i', strtotime($lobby['waktu_kumpul'])); ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="info-card">
                            <h4><i class="fas fa-users"></i> Players</h4>
                            <p><?php echo $lobby['current_players']; ?> / <?php echo $lobby['max_players']; ?> pemain</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="detail-sidebar">
                    <!-- Host Card -->
                    <div class="sidebar-card">
                        <h3>Host</h3>
                        <div class="host-card">
                            <img src="uploads/<?php echo $lobby['avatar']; ?>" alt="<?php echo $lobby['username']; ?>">
                            <div class="host-info">
                                <h4><?php echo $lobby['username']; ?></h4>
                                <span style="color: <?php echo getRankColor($lobby['rank']); ?>; font-weight: 600;">
                                    <?php echo $lobby['rank']; ?>
                                </span>
                                <div style="color: var(--text-muted); font-size: 0.9rem;">
                                    <i class="fas fa-star" style="color: var(--accent);"></i>
                                    <?php echo number_format($lobby['rating'], 1); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Members -->
                    <div class="sidebar-card">
                        <h3>Members (<?php echo mysqli_num_rows($members_result); ?>)</h3>
                        <div class="members-list">
                            <?php while ($member = mysqli_fetch_assoc($members_result)): ?>
                            <div class="member-item">
                                <img src="uploads/<?php echo $member['avatar']; ?>" alt="<?php echo $member['username']; ?>">
                                <div style="flex: 1;">
                                    <div style="font-weight: 600;"><?php echo $member['username']; ?></div>
                                    <div style="font-size: 0.85rem; color: <?php echo getRankColor($member['rank']); ?>">
                                        <?php echo $member['rank']; ?>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="sidebar-card">
                        <h3>Actions</h3>
                        <div class="action-buttons">
                            <?php if (isLoggedIn()): ?>
                                <?php if ($is_host): ?>
                                    <a href="lobby-edit.php?id=<?php echo $lobby_id; ?>" class="btn btn-primary btn-block">
                                        <i class="fas fa-edit"></i> Edit Lobby
                                    </a>
                                <?php elseif ($is_member): ?>
                                    <form method="POST">
                                        <button type="submit" name="leave_lobby" class="btn btn-outline btn-block" onclick="return confirm('Yakin ingin keluar dari lobby?')">
                                            <i class="fas fa-sign-out-alt"></i> Leave Lobby
                                        </button>
                                    </form>
                                <?php elseif ($lobby['status'] != 'Full'): ?>
                                    <form method="POST">
                                        <button type="submit" name="join_lobby" class="btn btn-primary btn-block">
                                            <i class="fas fa-user-plus"></i> Join Lobby
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-outline btn-block" disabled>
                                        <i class="fas fa-lock"></i> Lobby Penuh
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-primary btn-block">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Join
                                </a>
                            <?php endif; ?>

                            <button class="btn btn-outline btn-block" onclick="shareLobby()">
                                <i class="fas fa-share-alt"></i> Share Lobby
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <script>
        function shareLobby() {
            const url = window.location.href;
            if (navigator.share) {
                navigator.share({
                    title: '<?php echo $lobby['judul_aktivitas']; ?>',
                    text: 'Join lobby ini di CircleIn!',
                    url: url
                });
            } else {
                navigator.clipboard.writeText(url);
                alert('Link telah disalin!');
            }
        }
    </script>
</body>
</html>
