<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$user_id = $_SESSION['user_id'];

// Get user data
$user = getUserData($user_id);

// Get user lobbies
$lobbies_query = "SELECT l.*,
    (SELECT COUNT(*) FROM lobby_members WHERE lobby_id = l.id) as member_count
    FROM lobbies l
    WHERE l.user_id = $user_id
    ORDER BY l.created_at DESC";
$lobbies_result = mysqli_query($conn, $lobbies_query);

// Get stats
$stats_query = "SELECT
    (SELECT COUNT(*) FROM lobbies WHERE user_id = $user_id) as total_lobbies,
    (SELECT COUNT(*) FROM lobbies WHERE user_id = $user_id AND status = 'Open') as active_lobbies,
    (SELECT AVG(rating) FROM ratings WHERE rated_id = $user_id) as avg_rating,
    (SELECT COUNT(*) FROM ratings WHERE rated_id = $user_id) as total_ratings
";
$stats = mysqli_fetch_assoc(mysqli_query($conn, $stats_query));

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = sanitize($_POST['full_name']);
    $bio = sanitize($_POST['bio']);

    // Handle avatar upload
    $avatar = $user['avatar'];
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['avatar']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $new_filename)) {
                $avatar = $new_filename;

                // Delete old avatar
                if ($user['avatar'] != 'default-avatar.png' && file_exists('uploads/' . $user['avatar'])) {
                    unlink('uploads/' . $user['avatar']);
                }
            }
        }
    }

    $update_query = "UPDATE users SET
        full_name = '$full_name',
        bio = '$bio',
        avatar = '$avatar'
        WHERE id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['avatar'] = $avatar;
        logActivity($user_id, 'profile_update', 'User updated profile');
        showAlert('Profil berhasil diupdate!', 'success');
        redirect('profile.php');
    }
}

$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <i class="fas fa-home"></i> Home
                </a>
                <a href="lobby-create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Lobby
                </a>
                <a href="profile.php" class="nav-link active">
                    <img src="uploads/<?php echo $_SESSION['avatar']; ?>" class="nav-avatar">
                    <span><?php echo $_SESSION['username']; ?></span>
                </a>
                <a href="logout.php" class="nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <?php if ($alert): ?>
    <div class="alert alert-<?php echo $alert['type']; ?>">
        <div class="container">
            <?php echo $alert['message']; ?>
            <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-grid">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar-wrapper">
                                <img src="uploads/<?php echo $user['avatar']; ?>" alt="<?php echo $user['username']; ?>" class="profile-avatar">
                                <div class="rank-badge" style="background: <?php echo getRankColor($user['rank']); ?>">
                                    <?php echo $user['rank']; ?>
                                </div>
                            </div>
                            <h2><?php echo $user['username']; ?></h2>
                            <p class="profile-fullname"><?php echo $user['full_name']; ?></p>
                        </div>

                        <div class="profile-stats">
                            <div class="stat-item">
                                <span class="stat-value"><?php echo $stats['total_lobbies']; ?></span>
                                <span class="stat-label">Total Lobbies</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value"><?php echo $stats['active_lobbies']; ?></span>
                                <span class="stat-label">Active</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value"><?php echo number_format($stats['avg_rating'] ?? 0, 1); ?></span>
                                <span class="stat-label">Rating</span>
                            </div>
                        </div>

                        <div class="profile-bio">
                            <h4><i class="fas fa-info-circle"></i> Bio</h4>
                            <p><?php echo $user['bio'] ?: 'Belum ada bio.'; ?></p>
                        </div>

                        <div class="profile-info">
                            <div class="info-item">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo $user['email']; ?></span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <span>Bergabung <?php echo date('M Y', strtotime($user['created_at'])); ?></span>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-block" onclick="toggleEditModal()">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </aside>

                <!-- Main Content -->
                <main class="profile-main">
                    <!-- Tabs -->
                    <div class="profile-tabs">
                        <button class="tab-btn active" data-tab="lobbies">
                            <i class="fas fa-gamepad"></i> My Lobbies
                        </button>
                        <button class="tab-btn" data-tab="ratings">
                            <i class="fas fa-star"></i> Ratings
                        </button>
                        <button class="tab-btn" data-tab="activity">
                            <i class="fas fa-history"></i> Activity
                        </button>
                    </div>

                    <!-- Lobbies Tab -->
                    <div class="tab-content active" id="lobbies">
                        <div class="lobbies-grid">
                            <?php if (mysqli_num_rows($lobbies_result) > 0): ?>
                                <?php while ($lobby = mysqli_fetch_assoc($lobbies_result)): ?>
                                    <div class="lobby-card-sm">
                                        <div class="lobby-card-header">
                                            <span class="lobby-category category-<?php echo strtolower($lobby['kategori']); ?>">
                                                <?php echo $lobby['kategori']; ?>
                                            </span>
                                            <span class="lobby-status status-<?php echo strtolower($lobby['status']); ?>">
                                                <?php echo $lobby['status']; ?>
                                            </span>
                                        </div>
                                        <h3><?php echo $lobby['judul_aktivitas']; ?></h3>
                                        <p><?php echo substr($lobby['deskripsi'], 0, 80); ?>...</p>
                                        <div class="lobby-card-footer">
                                            <span><i class="fas fa-users"></i> <?php echo $lobby['current_players']; ?>/<?php echo $lobby['max_players']; ?></span>
                                            <span><i class="fas fa-clock"></i> <?php echo timeAgo($lobby['created_at']); ?></span>
                                        </div>
                                        <div class="lobby-actions">
                                            <a href="lobby-detail.php?id=<?php echo $lobby['id']; ?>" class="btn btn-sm btn-outline">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="lobby-edit.php?id=<?php echo $lobby['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>Belum Ada Lobby</h3>
                                    <p>Anda belum membuat lobby. Mulai cari teman sekarang!</p>
                                    <a href="lobby-create.php" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Buat Lobby
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Ratings Tab -->
                    <div class="tab-content" id="ratings">
                        <div class="ratings-section">
                            <h3>Coming Soon</h3>
                            <p>Fitur rating akan segera hadir!</p>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-content" id="activity">
                        <div class="activity-section">
                            <h3>Coming Soon</h3>
                            <p>History aktivitas akan segera hadir!</p>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profile</h3>
                <button class="modal-close" onclick="toggleEditModal()">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Avatar</label>
                        <input type="file" name="avatar" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Bio</label>
                        <textarea name="bio" rows="4"><?php echo $user['bio']; ?></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="toggleEditModal()">Batal</button>
                    <button type="submit" name="update_profile" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.dataset.tab;

                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

                btn.classList.add('active');
                document.getElementById(tabName).classList.add('active');
            });
        });

        // Modal toggle
        function toggleEditModal() {
            document.getElementById('editModal').classList.toggle('active');
        }

        // Close modal on outside click
        window.addEventListener('click', (e) => {
            const modal = document.getElementById('editModal');
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    </script>
</body>
</html>
