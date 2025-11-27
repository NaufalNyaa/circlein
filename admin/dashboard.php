<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Get statistics
$stats = [];

// Total users
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$stats['users'] = mysqli_fetch_assoc($result)['total'];

// Total lobbies
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM lobbies");
$stats['lobbies'] = mysqli_fetch_assoc($result)['total'];

// Active lobbies
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM lobbies WHERE status = 'Open'");
$stats['active_lobbies'] = mysqli_fetch_assoc($result)['total'];

// Today's activities
$result = mysqli_query($conn, "SELECT COUNT(*) as total FROM activity_logs WHERE DATE(created_at) = CURDATE()");
$stats['today_activities'] = mysqli_fetch_assoc($result)['total'];

// Recent activities
$recent_activities = mysqli_query($conn, "
    SELECT al.*, u.username, u.avatar
    FROM activity_logs al
    LEFT JOIN users u ON al.user_id = u.id
    ORDER BY al.created_at DESC
    LIMIT 10
");

// Recent users
$recent_users = mysqli_query($conn, "
    SELECT * FROM users
    WHERE role = 'user'
    ORDER BY created_at DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="users.php" class="nav-item">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                    <span class="badge"><?php echo $stats['users']; ?></span>
                </a>
                <a href="lobbies.php" class="nav-item">
                    <i class="fas fa-gamepad"></i>
                    <span>Lobbies</span>
                    <span class="badge"><?php echo $stats['lobbies']; ?></span>
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
            <!-- Topbar -->
            <div class="admin-topbar">
                <div class="topbar-left">
                    <h1>Dashboard</h1>
                    <p>Selamat datang kembali, <?php echo $_SESSION['username']; ?>!</p>
                </div>
                <div class="topbar-right">
                    <div class="topbar-user">
                        <img src="../uploads/<?php echo $_SESSION['avatar']; ?>" alt="Avatar">
                        <div>
                            <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                            <span class="user-role">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card card-primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-value"><?php echo number_format($stats['users']); ?></span>
                        <span class="stat-label">Total Users</span>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> 12%
                    </div>
                </div>

                <div class="stat-card card-secondary">
                    <div class="stat-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-value"><?php echo number_format($stats['lobbies']); ?></span>
                        <span class="stat-label">Total Lobbies</span>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> 8%
                    </div>
                </div>

                <div class="stat-card card-success">
                    <div class="stat-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-value"><?php echo number_format($stats['active_lobbies']); ?></span>
                        <span class="stat-label">Active Lobbies</span>
                    </div>
                    <div class="stat-trend up">
                        <i class="fas fa-arrow-up"></i> 24%
                    </div>
                </div>

                <div class="stat-card card-warning">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-details">
                        <span class="stat-value"><?php echo number_format($stats['today_activities']); ?></span>
                        <span class="stat-label">Today's Activities</span>
                    </div>
                    <div class="stat-trend down">
                        <i class="fas fa-arrow-down"></i> 3%
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Activities -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-history"></i>
                            Recent Activities
                        </h3>
                        <a href="activities.php" class="link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="activity-list">
                            <?php while ($activity = mysqli_fetch_assoc($recent_activities)): ?>
                                <div class="activity-item">
                                    <?php if ($activity['avatar']): ?>
                                        <img src="../uploads/<?php echo $activity['avatar']; ?>" alt="Avatar" class="activity-avatar">
                                    <?php else: ?>
                                        <div class="activity-avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="activity-details">
                                        <p class="activity-text">
                                            <strong><?php echo $activity['username'] ?? 'System'; ?></strong>
                                            <?php echo $activity['description'] ?? $activity['action']; ?>
                                        </p>
                                        <span class="activity-time"><?php echo timeAgo($activity['created_at']); ?></span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="admin-card">
                    <div class="card-header">
                        <h3>
                            <i class="fas fa-user-plus"></i>
                            Recent Users
                        </h3>
                        <a href="users.php" class="link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="user-list">
                            <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                                <div class="user-item">
                                    <img src="../uploads/<?php echo $user['avatar']; ?>" alt="<?php echo $user['username']; ?>">
                                    <div class="user-info">
                                        <span class="user-name"><?php echo $user['username']; ?></span>
                                        <span class="user-rank" style="color: <?php echo getRankColor($user['rank']); ?>">
                                            <?php echo $user['rank']; ?>
                                        </span>
                                    </div>
                                    <span class="user-date"><?php echo timeAgo($user['created_at']); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="admin-card">
                <div class="card-header">
                    <h3>
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="users.php?action=add" class="action-btn btn-primary">
                            <i class="fas fa-user-plus"></i>
                            <span>Add User</span>
                        </a>
                        <a href="lobbies.php?action=moderate" class="action-btn btn-warning">
                            <i class="fas fa-flag"></i>
                            <span>Moderate Lobbies</span>
                        </a>
                        <a href="settings.php" class="action-btn btn-secondary">
                            <i class="fas fa-cog"></i>
                            <span>Site Settings</span>
                        </a>
                        <a href="activities.php?filter=today" class="action-btn btn-info">
                            <i class="fas fa-chart-line"></i>
                            <span>View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Auto refresh stats every 30 seconds
        setInterval(() => {
            // Add AJAX call here to refresh stats
        }, 30000);

        // Add smooth transitions
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!this.classList.contains('active')) {
                    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
