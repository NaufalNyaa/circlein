<?php
require_once '../config.php';

if (!isLoggedIn() || !isAdmin()) {
    redirect('../login.php');
}

$alert = getAlert();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .settings-grid {
            display: grid;
            gap: 2rem;
        }

        .setting-card {
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
        }

        .setting-card h3 {
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: var(--dark-lighter);
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .setting-info h4 {
            margin-bottom: 0.25rem;
        }

        .setting-info p {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--dark);
            transition: .4s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(30px);
        }

        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-box {
            background: var(--dark-lighter);
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
        }

        .stat-box h4 {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-box .value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
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
                <a href="activities.php" class="nav-item">
                    <i class="fas fa-history"></i>
                    <span>Activity Logs</span>
                </a>
                <a href="settings.php" class="nav-item active">
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

            <h1 style="margin-bottom: 2rem;">Settings</h1>

            <!-- System Stats -->
            <div class="stats-overview">
                <?php
                $total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE role='user'"))['count'];
                $total_lobbies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM lobbies"))['count'];
                $active_lobbies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM lobbies WHERE status='Open'"))['count'];
                $total_activities = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM activity_logs"))['count'];
                ?>
                <div class="stat-box">
                    <h4>Total Users</h4>
                    <div class="value"><?php echo number_format($total_users); ?></div>
                </div>
                <div class="stat-box">
                    <h4>Total Lobbies</h4>
                    <div class="value"><?php echo number_format($total_lobbies); ?></div>
                </div>
                <div class="stat-box">
                    <h4>Active Lobbies</h4>
                    <div class="value"><?php echo number_format($active_lobbies); ?></div>
                </div>
                <div class="stat-box">
                    <h4>Total Activities</h4>
                    <div class="value"><?php echo number_format($total_activities); ?></div>
                </div>
            </div>

            <div class="settings-grid">
                <!-- General Settings -->
                <div class="setting-card">
                    <h3><i class="fas fa-cog"></i> General Settings</h3>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Site Name</h4>
                            <p>Current: <?php echo SITE_NAME; ?></p>
                        </div>
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Site URL</h4>
                            <p><?php echo SITE_URL; ?></p>
                        </div>
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Maintenance Mode</h4>
                            <p>Put site in maintenance mode</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <!-- User Settings -->
                <div class="setting-card">
                    <h3><i class="fas fa-users"></i> User Settings</h3>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>User Registration</h4>
                            <p>Allow new user registrations</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Email Verification</h4>
                            <p>Require email verification for new users</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Default Rank</h4>
                            <p>Default rank for new users: Bronze</p>
                        </div>
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                </div>

                <!-- Lobby Settings -->
                <div class="setting-card">
                    <h3><i class="fas fa-gamepad"></i> Lobby Settings</h3>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Max Lobby Players</h4>
                            <p>Maximum players allowed per lobby: 50</p>
                        </div>
                        <button class="btn btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Auto-Close Lobbies</h4>
                            <p>Automatically close expired lobbies</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Featured Lobbies</h4>
                            <p>Enable featured lobby system</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Database Maintenance -->
                <div class="setting-card">
                    <h3><i class="fas fa-database"></i> Database Maintenance</h3>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Clear Old Activity Logs</h4>
                            <p>Delete activity logs older than 30 days</p>
                        </div>
                        <button class="btn btn-sm" style="background: var(--warning);">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Clear Closed Lobbies</h4>
                            <p>Delete closed lobbies older than 7 days</p>
                        </div>
                        <button class="btn btn-sm" style="background: var(--warning);">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Database Backup</h4>
                            <p>Create a backup of the database</p>
                        </div>
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Backup
                        </button>
                    </div>
                </div>

                <!-- System Info -->
                <div class="setting-card">
                    <h3><i class="fas fa-info-circle"></i> System Information</h3>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>PHP Version</h4>
                            <p><?php echo phpversion(); ?></p>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>MySQL Version</h4>
                            <p><?php echo mysqli_get_server_info($conn); ?></p>
                        </div>
                    </div>

                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Upload Max Size</h4>
                            <p><?php echo ini_get('upload_max_filesize'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle switch functionality
        document.querySelectorAll('.toggle-switch input').forEach(toggle => {
            toggle.addEventListener('change', function() {
                console.log('Setting changed:', this.checked);
                // Add AJAX call here to save settings
            });
        });
    </script>
</body>
</html>
