<?php
include('../config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($conn, "UPDATE registrations SET status='Dikonfirmasi' WHERE id=$id");
}

header("Location: admin-registrations.php");
exit;
?>
