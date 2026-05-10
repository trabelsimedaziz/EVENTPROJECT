<?php
session_start();
require_once "session.php";
require_once "models.php";

// user submits a new event request
if(isset($_POST['request_btn'])) {
    $req = new Request();
    $req->user_id = $_SESSION['user_id'];
    $req->event_name = $_POST['event_name'];
    $req->event_date = $_POST['event_date'];
    $req->insertRequest();
    header('location:booking.php');
    exit;
}

// admin approves or declines a request
if(isset($_POST['action'])) {
    Verify_session();
    Verify_admin();
    $req = new Request();
    $status = ($_POST['action'] == 'approve') ? 'approved' : 'declined';
    $price = $_POST['price'] ?? 0;
    $req->updateStatus($_POST['id'], $status, $price);
    header('location:booking.php');
    exit;
}
?>
