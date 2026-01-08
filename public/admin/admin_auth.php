<?php
session_start();

// Pastikan menggunakan variabel sesi yang sama seperti di admin-login.php
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php");
    exit();
}
?>
