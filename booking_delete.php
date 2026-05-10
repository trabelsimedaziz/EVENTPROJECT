<?php
session_start();
require_once "session.php";
require_once "models.php";
Verify_session();

$id = $_GET['id'];
$bookingModel = new Booking();
$bookingModel->deleteBooking($id);
header('location:bookings.php');
?>
