<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['registration_id'])) {
    $registration_id = intval($_GET['registration_id']);
    mysqli_query($conn, "DELETE FROM registrations WHERE id=$registration_id AND user_id=$user_id");
}

header("Location: profile.php");
exit;
?>
