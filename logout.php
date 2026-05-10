<?php
session_start();
// Basic logout, simple as Lab 3
session_destroy();
header('location:index.php');
?>
