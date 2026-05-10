<?php
require_once "header.php";
require_once "models.php";

if (isset($_POST['register_btn'])) {
    if($_POST['password'] === $_POST['confirm_password']) {
        $acc = new Account();
        $acc->email = $_POST['email'];
        $acc->password = $_POST['password'];
        $acc->register();
        header('location:login.php');
    } else {
        echo "<p style='color:red'>Passwords do not match!</p>";
    }
}
?>

<h2>Register</h2>
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required>
    <button type="submit" name="register_btn" class="btn btn-primary">Register</button>
</form>

</div>
</body>
</html>
