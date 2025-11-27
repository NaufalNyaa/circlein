<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['ban_user'])) {
        $user_id = intval($_POST['user_id']);
        mysqli_query($conn, "UPDATE users SET status = 'banned' WHERE id = $user_id");
        showAlert('User berhasil dibanned!', 'success');
    }

    if (isset($_POST['unban_user'])) {
        $user_id = intval($_POST['user_id']);
        mysqli_query($conn, "UPDATE users SET status = 'active' WHERE id = $user_id");
        showAlert('User berhasil di-unban!', 'success');
    }

    if (isset($_POST['delete_user'])) {
        $user_id = intval($_POST['user_id']);
        mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
        showAlert('User berhasil dihapus!', 'success');
    }

    redirect('users.php');
}

// Get filter
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$status = isset($_GET['status']) ? sanitize($_GET['status']) : '';

// Get users
$query = "SELECT * FROM users WHERE role = 'user'";
if ($search) {
    $query .= " AND (username LIKE '%$search%' OR email LIKE '%$search%' OR full_name LIKE '%$search%')";
}
if ($status) {
    $query .= " AND status = '$status'";
}
$query .= " ORDER BY created_at DESC";

$users = mysqli_query($conn, $query);
$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .users-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .filters input, .filters select {
            padding: 0.75rem;
            background: var(--dark-lighter);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 8px;
            color: var(--text);
        }

        .users-table {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 240, 255, 0.1);
        }

        th {
            background: var(--dark-lighter);
            color: var(--primary);
            font-weight: 600;
        }

        tr:hover {
            background: rgba(0, 240, 255, 0.05);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary);
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
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
                <a href="users.php" class="nav-item active">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
                <a href="lobbies.php" class="nav-item">
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

            <div class="users-header">
                <h1>Manage Users</h1>
            </div>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" style="display: flex; gap: 1rem; flex: 1;">
                    <input type="text" name="search" placeholder="Search users..." value="<?php echo $search; ?>" style="flex: 1;">
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php echo $status == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="banned" <?php echo $status == 'banned' ? 'selected' : ''; ?>>Banned</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </form>
            </div>

            <!-- Users Table -->
            <div class="users-table">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Rank</th>
                            <th>Lobbies</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td>
                                <div class="user-info">
                                    <img src="../uploads/<?php echo $user['avatar']; ?>" alt="<?php echo $user['username']; ?>">
                                    <div>
                                        <div style="font-weight: 600;"><?php echo $user['username']; ?></div>
                                        <div style="font-size: 0.85rem; color: var(--text-muted);"><?php echo $user['full_name']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo $user['email']; ?></td>
                            <td>
                                <span style="color: <?php echo getRankColor($user['rank']); ?>; font-weight: 600;">
                                    <?php echo $user['rank']; ?>
                                </span>
                            </td>
                            <td><?php echo $user['total_lobbies']; ?></td>
                            <td>
                                <?php if ($user['status'] == 'active'): ?>
                                    <span class="status-open">Active</span>
                                <?php else: ?>
                                    <span class="status-full">Banned</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <div class="action-btns">
                                    <?php if ($user['status'] == 'active'): ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="ban_user" class="btn btn-sm" style="background: var(--warning);" onclick="return confirm('Ban user ini?')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="unban_user" class="btn btn-sm btn-primary">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-sm" style="background: var(--danger);" onclick="return confirm('Hapus user ini? Tindakan tidak dapat dibatalkan!')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
