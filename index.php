<?php
require_once 'config.php';

// Get filter parameters
$kategori = isset($_GET['kategori']) ? sanitize($_GET['kategori']) : '';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

// Build query
$query = "SELECT l.*, u.username, u.avatar, u.rank FROM lobbies l
          JOIN users u ON l.user_id = u.id
          WHERE l.status != 'Closed'";

if ($kategori) {
    $query .= " AND l.kategori = '$kategori'";
}
if ($search) {
    $query .= " AND (l.judul_aktivitas LIKE '%$search%' OR l.deskripsi LIKE '%$search%')";
}

$query .= " ORDER BY l.featured DESC, l.created_at DESC";
$result = mysqli_query($conn, $query);

$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Find Your Gaming Squad</title>
    <link rel="stylesheet" href="assets/css/style.css">
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

            <div class="nav-search">
                <form action="" method="GET" id="searchForm">
                    <input type="text" name="search" placeholder="Cari lobby..." value="<?php echo $search; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="nav-menu">
                <?php if (isLoggedIn()): ?>
                    <a href="lobby-create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Lobby
                    </a>
                    <a href="profile.php" class="nav-link">
                        <img src="uploads/<?php echo $_SESSION['avatar']; ?>" class="nav-avatar">
                        <span><?php echo $_SESSION['username']; ?></span>
                    </a>
                    <?php if (isAdmin()): ?>
                        <a href="admin/dashboard.php" class="nav-link">
                            <i class="fas fa-shield-alt"></i> Admin
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Alert -->
    <?php if ($alert): ?>
    <div class="alert alert-<?php echo $alert['type']; ?>">
        <div class="container">
            <?php echo $alert['message']; ?>
            <button class="alert-close" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="glitch" data-text="Find Your Squad">Find Your Squad</h1>
                <p>Cari teman mabar, olahraga, belajar, atau hangout. Semua dalam satu platform!</p>
                <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-rocket"></i> Mulai Sekarang
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="filter-section">
        <div class="container">
            <div class="filter-tabs">
                <a href="index.php" class="filter-tab <?php echo !$kategori ? 'active' : ''; ?>">
                    <i class="fas fa-fire"></i> Semua
                </a>
                <a href="?kategori=Game" class="filter-tab <?php echo $kategori == 'Game' ? 'active' : ''; ?>">
                    <i class="fas fa-gamepad"></i> Game
                </a>
                <a href="?kategori=Olahraga" class="filter-tab <?php echo $kategori == 'Olahraga' ? 'active' : ''; ?>">
                    <i class="fas fa-futbol"></i> Olahraga
                </a>
                <a href="?kategori=Belajar" class="filter-tab <?php echo $kategori == 'Belajar' ? 'active' : ''; ?>">
                    <i class="fas fa-book"></i> Belajar
                </a>
                <a href="?kategori=Hangout" class="filter-tab <?php echo $kategori == 'Hangout' ? 'active' : ''; ?>">
                    <i class="fas fa-coffee"></i> Hangout
                </a>
                <a href="?kategori=Kompetisi" class="filter-tab <?php echo $kategori == 'Kompetisi' ? 'active' : ''; ?>">
                    <i class="fas fa-trophy"></i> Kompetisi
                </a>
            </div>
        </div>
    </section>

    <!-- Lobbies Grid -->
    <section class="lobbies-section">
        <div class="container">
            <div class="lobbies-grid">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($lobby = mysqli_fetch_assoc($result)): ?>
                        <div class="lobby-card <?php echo $lobby['featured'] ? 'featured' : ''; ?>" onclick="viewLobby(<?php echo $lobby['id']; ?>)">
                            <?php if ($lobby['featured']): ?>
                                <div class="featured-badge">
                                    <i class="fas fa-star"></i> Featured
                                </div>
                            <?php endif; ?>

                            <div class="lobby-header">
                                <div class="lobby-category category-<?php echo strtolower($lobby['kategori']); ?>">
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

                            <p class="lobby-description"><?php echo substr($lobby['deskripsi'], 0, 100); ?>...</p>

                            <div class="lobby-info">
                                <?php if ($lobby['lokasi']): ?>
                                    <div class="lobby-info-item">
                                        <i class="fas fa-map-marker-alt"></i> <?php echo $lobby['lokasi']; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($lobby['waktu_kumpul']): ?>
                                    <div class="lobby-info-item">
                                        <i class="fas fa-clock"></i> <?php echo date('d M, H:i', strtotime($lobby['waktu_kumpul'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="lobby-footer">
                                <div class="lobby-host">
                                    <img src="uploads/<?php echo $lobby['avatar']; ?>" alt="<?php echo $lobby['username']; ?>">
                                    <div class="host-info">
                                        <span class="host-name"><?php echo $lobby['username']; ?></span>
                                        <span class="host-rank" style="color: <?php echo getRankColor($lobby['rank']); ?>">
                                            <?php echo $lobby['rank']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="lobby-players">
                                    <i class="fas fa-users"></i>
                                    <span><?php echo $lobby['current_players']; ?>/<?php echo $lobby['max_players']; ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h3>Tidak Ada Lobby</h3>
                        <p>Belum ada lobby yang sesuai dengan kriteria pencarian Anda.</p>
                        <?php if (isLoggedIn()): ?>
                            <a href="lobby-create.php" class="btn btn-primary">Buat Lobby Pertama</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <i class="fas fa-gamepad"></i>
                    <span>CircleIn</span>
                </div>
                <p>&copy; 2025 CircleIn. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">About</a>
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function viewLobby(id) {
            window.location.href = 'lobby-detail.php?id=' + id;
        }

        // Add smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
