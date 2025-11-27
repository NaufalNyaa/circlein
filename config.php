<?php
// CircleIn v2.0 - Configuration File
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_circlein');

// Site Configuration
define('SITE_NAME', 'CircleIn');
define('SITE_URL', 'http://localhost/circlein/');
define('UPLOAD_PATH', 'uploads/');

// Connect to Database
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Helper Functions
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function redirect($url) {
    header("Location: " . $url);
    exit;
}

function sanitize($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($data))));
}

function showAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

function getAlert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        unset($_SESSION['alert']);
        return $alert;
    }
    return null;
}

function getUserData($user_id) {
    global $conn;
    $query = "SELECT * FROM users WHERE id = " . intval($user_id);
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function logActivity($user_id, $action, $description = null) {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO activity_logs (user_id, action, description, ip_address)
              VALUES ('$user_id', '$action', '$description', '$ip')";
    mysqli_query($conn, $query);
}

function timeAgo($timestamp) {
    $time = strtotime($timestamp);
    $diff = time() - $time;

    if ($diff < 60) return $diff . ' detik lalu';
    if ($diff < 3600) return floor($diff / 60) . ' menit lalu';
    if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
    if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';

    return date('d M Y', $time);
}

function getRankColor($rank) {
    $colors = [
        'Bronze' => '#CD7F32',
        'Silver' => '#C0C0C0',
        'Gold' => '#FFD700',
        'Platinum' => '#E5E4E2',
        'Diamond' => '#B9F2FF'
    ];
    return $colors[$rank] ?? '#999';
}
?>
