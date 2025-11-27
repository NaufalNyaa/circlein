<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE (username = '$username' OR email = '$username') AND status = 'active'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['avatar'] = $user['avatar'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['rank'] = $user['rank'];

            // Update last login
            $update = "UPDATE users SET last_login = NOW() WHERE id = " . $user['id'];
            mysqli_query($conn, $update);

            logActivity($user['id'], 'login', 'User logged in');

            showAlert('Selamat datang kembali, ' . $user['username'] . '!', 'success');
            redirect('index.php');
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Username atau email tidak ditemukan!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-bg">
            <div class="bg-shape shape-1"></div>
            <div class="bg-shape shape-2"></div>
            <div class="bg-shape shape-3"></div>
        </div>

        <div class="auth-box">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-gamepad"></i>
                    <span>CircleIn</span>
                </div>
                <h1>Welcome Back</h1>
                <p>Login untuk melanjutkan petualangan!</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="auth-alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Username atau Email
                    </label>
                    <input type="text" name="username" required autofocus>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="link">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Login Sekarang
                </button>
            </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="register.php" class="link">Daftar Sekarang</a></p>
                <div class="auth-divider">
                    <span>atau</span>
                </div>
                <a href="index.php" class="btn btn-outline btn-block">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Add floating animation to shapes
        const shapes = document.querySelectorAll('.bg-shape');
        shapes.forEach((shape, index) => {
            const duration = 15 + index * 5;
            const delay = index * 2;
            shape.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
        });
    </script>
</body>
</html>
