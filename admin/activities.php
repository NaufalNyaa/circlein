<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// filter
$filter = isset($_GET['filter']) ? sanitize($_GET['filter']) : '';

//aktifitas
$query = "SELECT al.*, u.username, u.avatar
          FROM activity_logs al
          LEFT JOIN users u ON al.user_id = u.id";

if ($filter == 'today') {
    $query .= " WHERE DATE(al.created_at) = CURDATE()";
} elseif ($filter == 'week') {
    $query .= " WHERE al.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
}

$query .= " ORDER BY al.created_at DESC LIMIT 100";

$activities = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .activities-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .filter-tab {
            padding: 0.75rem 1.5rem;
            background: var(--dark-lighter);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 8px;
            color: var(--text);
            text-decoration: none;
            transition: all 0.3s;
        }

        .filter-tab.active {
            background: var(--primary);
            color: var(--dark);
        }

        .activities-list {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .activity-item {
            display: flex;
            gap: 1rem;
            padding: 1rem;
            background: var(--dark-lighter);
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .activity-content {
            flex: 1;
        }

        .activity-action {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(0, 240, 255, 0.1);
            border-radius: 20px;
            font-size: 0.85rem;
            color: var(--primary);
            margin-bottom: 0.5rem;
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
                <a href="lobbies.php" class="nav-item">
                    <i class="fas fa-gamepad"></i>
                    <span>Lobbies</span>
                </a>
                <a href="activities.php" class="nav-item active">
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
            <div class="activities-header">
                <h1>Activity Logs</h1>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <a href="activities.php" class="filter-tab <?php echo !$filter ? 'active' : ''; ?>">
                    All Activities
                </a>
                <a href="activities.php?filter=today" class="filter-tab <?php echo $filter == 'today' ? 'active' : ''; ?>">
                    Today
                </a>
                <a href="activities.php?filter=week" class="filter-tab <?php echo $filter == 'week' ? 'active' : ''; ?>">
                    This Week
                </a>
            </div>

            <!-- Activities List -->
            <div class="activities-list">
                <?php while ($activity = mysqli_fetch_assoc($activities)): ?>
                <div class="activity-item">
                    <?php if ($activity['avatar']): ?>
                        <img src="../uploads/<?php echo $activity['avatar']; ?>" alt="Avatar" class="activity-avatar">
                    <?php else: ?>
                        <div class="activity-avatar" style="background: var(--dark); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="color: var(--text-muted);"></i>
                        </div>
                    <?php endif; ?>

                    <div class="activity-content">
                        <span class="activity-action"><?php echo $activity['action']; ?></span>
                        <p style="margin-bottom: 0.5rem;">
                            <strong><?php echo $activity['username'] ?? 'System'; ?></strong>
                            <?php echo $activity['description'] ?? $activity['action']; ?>
                        </p>
                        <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: var(--text-muted);">
                            <span><i class="fas fa-clock"></i> <?php echo timeAgo($activity['created_at']); ?></span>
                            <span><i class="fas fa-network-wired"></i> <?php echo $activity['ip_address']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>
</body>
</html>
