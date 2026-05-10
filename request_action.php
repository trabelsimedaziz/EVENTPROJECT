<?php
session_start();
require_once "session.php";
require_once "models.php";
Verify_session();
Verify_admin();

if (isset($_POST['action'])) {
    $req = new Request();
    $status = ($_POST['action'] == 'approve') ? 'approved' : 'declined';
    $price = $_POST['price'] ?? 0;
    $req->updateStatus($_POST['id'], $status, $price);
    header('location:bookings.php');
}
?>
