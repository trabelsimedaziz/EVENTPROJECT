<?php
function Verify_session() {
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== "1") {
        header('location:login.php');
        exit();
    }
}

function Verify_admin() {
    if ($_SESSION['role'] !== 'admin') {
        echo "Access Denied. Admins only.";
        exit();
    }
}
?>
