<?php
session_start();
require_once "models.php";
if (isset($_POST['request_btn'])) {
    $req = new Request();
    $req->user_id = $_SESSION['user_id'];
    $req->event_name = $_POST['event_name'];
    $req->event_date = $_POST['event_date'];
    $req->insertRequest();
    header('location:bookings.php');
}
?>
