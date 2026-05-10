<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Booking System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <div>
            <strong>EventSystem</strong>
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['logged'])): ?>
                <a href="events.php">Events</a>
                <a href="bookings.php">Bookings</a>
            <?php endif; ?>
        </div>
        <div>
            <?php if(isset($_SESSION['logged'])): ?>
                <span><?php echo $_SESSION['user_email']; ?> (<?php echo $_SESSION['role']; ?>)</span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
<div class="container">
