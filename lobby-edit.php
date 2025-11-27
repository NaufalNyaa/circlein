<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

$lobby_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = $_SESSION['user_id'];

if (!$lobby_id) {
    redirect('index.php');
}

// Get lobby
$query = "SELECT * FROM lobbies WHERE id = $lobby_id AND user_id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    showAlert('Lobby tidak ditemukan atau Anda bukan host!', 'danger');
    redirect('index.php');
}

$lobby = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_lobby'])) {
    $judul = sanitize($_POST['judul_aktivitas']);
    $kategori = sanitize($_POST['kategori']);
    $game_type = sanitize($_POST['game_type'] ?? '');
    $deskripsi = sanitize($_POST['deskripsi']);
    $lokasi = sanitize($_POST['lokasi']);
    $waktu_kumpul = sanitize($_POST['waktu_kumpul']);
    $max_players = intval($_POST['max_players']);
    $status = sanitize($_POST['status']);

    $update_query = "UPDATE lobbies SET
        judul_aktivitas = '$judul',
        kategori = '$kategori',
        game_type = '$game_type',
        deskripsi = '$deskripsi',
        lokasi = '$lokasi',
        waktu_kumpul = '$waktu_kumpul',
        max_players = $max_players,
        status = '$status'
        WHERE id = $lobby_id AND user_id = $user_id";

    if (mysqli_query($conn, $update_query)) {
        logActivity($user_id, 'update_lobby', "Updated lobby: $judul");
        showAlert('Lobby berhasil diupdate!', 'success');
        redirect('lobby-detail.php?id=' . $lobby_id);
    } else {
        $error = 'Gagal mengupdate lobby!';
    }
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_lobby'])) {
    if (mysqli_query($conn, "DELETE FROM lobbies WHERE id = $lobby_id AND user_id = $user_id")) {
        logActivity($user_id, 'delete_lobby', "Deleted lobby: {$lobby['judul_aktivitas']}");
        showAlert('Lobby berhasil dihapus!', 'success');
        redirect('profile.php');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lobby - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-container {
            min-height: 100vh;
            padding: 8rem 2rem 4rem;
        }

        .edit-box {
            max-width: 800px;
            margin: 0 auto;
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 24px;
            padding: 3rem;
        }

        .edit-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .edit-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .category-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .category-option {
            position: relative;
        }

        .category-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .category-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1.5rem 1rem;
            background: var(--dark-lighter);
            border: 2px solid transparent;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .category-label i {
            font-size: 2.5rem;
        }

        .category-option input[type="radio"]:checked + .category-label {
            border-color: var(--primary);
            background: rgba(0, 240, 255, 0.1);
            transform: scale(1.05);
        }

        .game-type-field {
            display: none;
        }

        .game-type-field.active {
            display: block;
        }

        .danger-zone {
            margin-top: 3rem;
            padding: 2rem;
            border: 1px solid var(--danger);
            border-radius: 16px;
            background: rgba(255, 71, 87, 0.05);
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
                <a href="lobby-detail.php?id=<?php echo $lobby_id; ?>" class="nav-link">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </nav>

    <div class="edit-container">
        <div class="edit-box">
            <div class="edit-header">
                <h1>Edit Lobby</h1>
                <p>Perbarui informasi lobby Anda</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="auth-alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <!-- Category Selection -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-th-large"></i>
                        Kategori
                    </label>
                    <div class="category-selector">
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-game" value="Game" <?php echo $lobby['kategori'] == 'Game' ? 'checked' : ''; ?>>
                            <label for="cat-game" class="category-label">
                                <i class="fas fa-gamepad"></i>
                                <span>Game</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-olahraga" value="Olahraga" <?php echo $lobby['kategori'] == 'Olahraga' ? 'checked' : ''; ?>>
                            <label for="cat-olahraga" class="category-label">
                                <i class="fas fa-futbol"></i>
                                <span>Olahraga</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-belajar" value="Belajar" <?php echo $lobby['kategori'] == 'Belajar' ? 'checked' : ''; ?>>
                            <label for="cat-belajar" class="category-label">
                                <i class="fas fa-book"></i>
                                <span>Belajar</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-hangout" value="Hangout" <?php echo $lobby['kategori'] == 'Hangout' ? 'checked' : ''; ?>>
                            <label for="cat-hangout" class="category-label">
                                <i class="fas fa-coffee"></i>
                                <span>Hangout</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-kompetisi" value="Kompetisi" <?php echo $lobby['kategori'] == 'Kompetisi' ? 'checked' : ''; ?>>
                            <label for="cat-kompetisi" class="category-label">
                                <i class="fas fa-trophy"></i>
                                <span>Kompetisi</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Game Type -->
                <div class="form-group game-type-field <?php echo $lobby['kategori'] == 'Game' ? 'active' : ''; ?>" id="gameTypeField">
                    <label>
                        <i class="fas fa-gamepad"></i>
                        Jenis Game
                    </label>
                    <select name="game_type">
                        <option value="">Pilih Jenis Game</option>
                        <option value="Mobile Legends" <?php echo $lobby['game_type'] == 'Mobile Legends' ? 'selected' : ''; ?>>Mobile Legends</option>
                        <option value="PUBG Mobile" <?php echo $lobby['game_type'] == 'PUBG Mobile' ? 'selected' : ''; ?>>PUBG Mobile</option>
                        <option value="Free Fire" <?php echo $lobby['game_type'] == 'Free Fire' ? 'selected' : ''; ?>>Free Fire</option>
                        <option value="Valorant" <?php echo $lobby['game_type'] == 'Valorant' ? 'selected' : ''; ?>>Valorant</option>
                        <option value="Dota 2" <?php echo $lobby['game_type'] == 'Dota 2' ? 'selected' : ''; ?>>Dota 2</option>
                        <option value="League of Legends" <?php echo $lobby['game_type'] == 'League of Legends' ? 'selected' : ''; ?>>League of Legends</option>
                        <option value="Genshin Impact" <?php echo $lobby['game_type'] == 'Genshin Impact' ? 'selected' : ''; ?>>Genshin Impact</option>
                        <option value="Call of Duty" <?php echo $lobby['game_type'] == 'Call of Duty' ? 'selected' : ''; ?>>Call of Duty</option>
                        <option value="Lainnya" <?php echo $lobby['game_type'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>

                <!-- Title -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-heading"></i>
                        Judul Aktivitas
                    </label>
                    <input type="text" name="judul_aktivitas" value="<?php echo $lobby['judul_aktivitas']; ?>" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i>
                        Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="4" required><?php echo $lobby['deskripsi']; ?></textarea>
                </div>

                <div class="form-row">
                    <!-- Location -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-map-marker-alt"></i>
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" value="<?php echo $lobby['lokasi']; ?>" required>
                    </div>

                    <!-- Max Players -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-users"></i>
                            Max Players
                        </label>
                        <input type="number" name="max_players" min="<?php echo $lobby['current_players']; ?>" max="50" value="<?php echo $lobby['max_players']; ?>" required>
                    </div>
                </div>

                <!-- Time -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-clock"></i>
                        Waktu Kumpul
                    </label>
                    <input type="datetime-local" name="waktu_kumpul" value="<?php echo date('Y-m-d\TH:i', strtotime($lobby['waktu_kumpul'])); ?>" required>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-toggle-on"></i>
                        Status Lobby
                    </label>
                    <select name="status" required>
                        <option value="Open" <?php echo $lobby['status'] == 'Open' ? 'selected' : ''; ?>>Open</option>
                        <option value="Full" <?php echo $lobby['status'] == 'Full' ? 'selected' : ''; ?>>Full</option>
                        <option value="Closed" <?php echo $lobby['status'] == 'Closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>

                <!-- Submit -->
                <div class="form-row" style="gap: 1rem;">
                    <button type="submit" name="update_lobby" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-save"></i>
                        Update Lobby
                    </button>
                    <a href="lobby-detail.php?id=<?php echo $lobby_id; ?>" class="btn btn-outline" style="flex: 1; text-align: center;">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>

            <!-- Danger Zone -->
            <div class="danger-zone">
                <h3 style="color: var(--danger); margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                </h3>
                <p style="color: var(--text-muted); margin-bottom: 1rem;">
                    Menghapus lobby akan menghapus semua data termasuk member. Tindakan ini tidak dapat dibatalkan!
                </p>
                <form method="POST" onsubmit="return confirm('Yakin ingin menghapus lobby ini? Tindakan ini tidak dapat dibatalkan!')">
                    <button type="submit" name="delete_lobby" class="btn" style="background: var(--danger); color: white;">
                        <i class="fas fa-trash"></i>
                        Hapus Lobby
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show/hide game type
        const categoryRadios = document.querySelectorAll('input[name="kategori"]');
        const gameTypeField = document.getElementById('gameTypeField');

        categoryRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.value === 'Game' && radio.checked) {
                    gameTypeField.classList.add('active');
                } else {
                    gameTypeField.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
