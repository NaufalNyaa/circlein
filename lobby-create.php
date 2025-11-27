<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $judul = sanitize($_POST['judul_aktivitas']);
    $kategori = sanitize($_POST['kategori']);
    $game_type = sanitize($_POST['game_type'] ?? '');
    $deskripsi = sanitize($_POST['deskripsi']);
    $lokasi = sanitize($_POST['lokasi']);
    $waktu_kumpul = sanitize($_POST['waktu_kumpul']);
    $max_players = intval($_POST['max_players']);

    $query = "INSERT INTO lobbies (
        user_id, judul_aktivitas, kategori, game_type, deskripsi,
        lokasi, waktu_kumpul, max_players, current_players
    ) VALUES (
        $user_id, '$judul', '$kategori', '$game_type', '$deskripsi',
        '$lokasi', '$waktu_kumpul', $max_players, 1
    )";

    if (mysqli_query($conn, $query)) {
        $lobby_id = mysqli_insert_id($conn);

        // Update user stats
        mysqli_query($conn, "UPDATE users SET total_lobbies = total_lobbies + 1 WHERE id = $user_id");

        // Add creator as member
        mysqli_query($conn, "INSERT INTO lobby_members (lobby_id, user_id) VALUES ($lobby_id, $user_id)");

        logActivity($user_id, 'create_lobby', "Created lobby: $judul");

        showAlert('Lobby berhasil dibuat!', 'success');
        redirect('lobby-detail.php?id=' . $lobby_id);
    } else {
        $error = 'Gagal membuat lobby. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Lobby - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .create-container {
            min-height: 100vh;
            padding: 8rem 2rem 4rem;
        }

        .create-box {
            max-width: 800px;
            margin: 0 auto;
            background: var(--dark-light);
            border: 1px solid rgba(0, 240, 255, 0.2);
            border-radius: 24px;
            padding: 3rem;
        }

        .create-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .create-header h1 {
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            </div>
        </div>
    </nav>

    <div class="create-container">
        <div class="create-box">
            <div class="create-header">
                <h1>Buat Lobby Baru</h1>
                <p>Cari teman untuk aktivitas seru kamu!</p>
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
                        Pilih Kategori
                    </label>
                    <div class="category-selector">
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-game" value="Game" required>
                            <label for="cat-game" class="category-label">
                                <i class="fas fa-gamepad"></i>
                                <span>Game</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-olahraga" value="Olahraga">
                            <label for="cat-olahraga" class="category-label">
                                <i class="fas fa-futbol"></i>
                                <span>Olahraga</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-belajar" value="Belajar">
                            <label for="cat-belajar" class="category-label">
                                <i class="fas fa-book"></i>
                                <span>Belajar</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-hangout" value="Hangout">
                            <label for="cat-hangout" class="category-label">
                                <i class="fas fa-coffee"></i>
                                <span>Hangout</span>
                            </label>
                        </div>
                        <div class="category-option">
                            <input type="radio" name="kategori" id="cat-kompetisi" value="Kompetisi">
                            <label for="cat-kompetisi" class="category-label">
                                <i class="fas fa-trophy"></i>
                                <span>Kompetisi</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Game Type (conditional) -->
                <div class="form-group game-type-field" id="gameTypeField">
                    <label>
                        <i class="fas fa-gamepad"></i>
                        Jenis Game
                    </label>
                    <select name="game_type" class="form-control">
                        <option value="">Pilih Jenis Game</option>
                        <option value="Mobile Legends">Mobile Legends</option>
                        <option value="PUBG Mobile">PUBG Mobile</option>
                        <option value="Free Fire">Free Fire</option>
                        <option value="Valorant">Valorant</option>
                        <option value="Dota 2">Dota 2</option>
                        <option value="League of Legends">League of Legends</option>
                        <option value="Genshin Impact">Genshin Impact</option>
                        <option value="Call of Duty">Call of Duty</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Activity Title -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-heading"></i>
                        Judul Aktivitas
                    </label>
                    <input type="text" name="judul_aktivitas" placeholder="Contoh: Cari Tanker Rank Epic" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i>
                        Deskripsi Detail
                    </label>
                    <textarea name="deskripsi" rows="4" placeholder="Jelaskan detail aktivitas, requirement, dll..." required></textarea>
                </div>

                <div class="form-row">
                    <!-- Location -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-map-marker-alt"></i>
                            Lokasi
                        </label>
                        <input type="text" name="lokasi" placeholder="Online / Alamat" required>
                    </div>

                    <!-- Max Players -->
                    <div class="form-group">
                        <label>
                            <i class="fas fa-users"></i>
                            Max Players
                        </label>
                        <input type="number" name="max_players" min="2" max="50" value="5" required>
                    </div>
                </div>

                <!-- Gathering Time -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-clock"></i>
                        Waktu Kumpul
                    </label>
                    <input type="datetime-local" name="waktu_kumpul" required>
                </div>

                <!-- Submit Buttons -->
                <div class="form-row" style="gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <i class="fas fa-rocket"></i>
                        Buat Lobby
                    </button>
                    <a href="index.php" class="btn btn-outline" style="flex: 1; text-align: center;">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show/hide game type field
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

        // Set minimum datetime to now
        const datetimeInput = document.querySelector('input[type="datetime-local"]');
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        datetimeInput.min = now.toISOString().slice(0, 16);

        // Form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', (e) => {
            const judul = document.querySelector('input[name="judul_aktivitas"]').value;
            const deskripsi = document.querySelector('textarea[name="deskripsi"]').value;

            if (judul.length < 5) {
                e.preventDefault();
                alert('Judul aktivitas minimal 5 karakter!');
                return;
            }

            if (deskripsi.length < 20) {
                e.preventDefault();
                alert('Deskripsi minimal 20 karakter!');
                return;
            }
        });
    </script>
</body>
</html>
