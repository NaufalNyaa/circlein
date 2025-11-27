<?php
require_once 'config.php';

if (isLoggedIn()) {
    $user_id = $_SESSION['user_id'];
    logActivity($user_id, 'logout', 'User logged out');
}

session_destroy();

showAlert('Anda telah logout. Sampai jumpa lagi☺️!', 'success');
redirect('index.php');
?>
