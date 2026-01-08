<?php
// /logout.php
session_start();
session_unset();
session_destroy();
$redirect = 'login.php';
if (isset($_GET['role']) && $_GET['role'] === 'admin') {
    $redirect = 'index.php';
}
header("Location: $redirect");
exit;
?>