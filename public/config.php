<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "the90academy"; // SESUAI database lokal kamu

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>

