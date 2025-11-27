<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $full_name = sanitize($_POST['full_name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    $errors = [];

    if (strlen($username) < 3) {
        $errors[] = 'Username minimal 3 karakter!';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid!';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password minimal 6 karakter!';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Password tidak cocok!';
    }

    // Check if username exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = 'Username sudah digunakan!';
    }

    // Check if email exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = 'Email sudah digunakan!';
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, full_name, password, avatar)
                  VALUES ('$username', '$email', '$full_name', '$hashed_password', 'default-avatar.png')";

        if (mysqli_query($conn, $query)) {
            $user_id = mysqli_insert_id($conn);
            logActivity($user_id, 'register', 'New user registered');

            showAlert('Registrasi berhasil! Silakan login.', 'success');
            redirect('login.php');
        } else {
            $errors[] = 'Registrasi gagal! Silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
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
                <h1>Join The Squad</h1>
                <p>Daftar dan mulai petualangan baru!</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="auth-alert error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo $error; ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Username
                    </label>
                    <input type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-envelope"></i>
                        Email
                    </label>
                    <input type="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-id-card"></i>
                        Nama Lengkap
                    </label>
                    <input type="text" name="full_name" value="<?php echo isset($_POST['full_name']) ? $_POST['full_name'] : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Password
                    </label>
                    <input type="password" name="password" id="password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                        <i class="fas fa-eye" id="toggleIcon1"></i>
                    </button>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-lock"></i>
                        Konfirmasi Password
                    </label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', 'toggleIcon2')">
                        <i class="fas fa-eye" id="toggleIcon2"></i>
                    </button>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" required>
                        <span>Saya setuju dengan <a href="#" class="link">Terms & Conditions</a></span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i>
                    Daftar Sekarang
                </button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="login.php" class="link">Login Sekarang</a></p>
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
        function togglePassword(inputId, iconId) {
            const password = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

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

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            // You can add visual indicator here
        });

        function checkPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;
            return strength;
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
