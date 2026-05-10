<?php
require_once "header.php";
require_once "models.php";

$error = "";
if (isset($_POST['login_btn'])) {
    $acc = new Account();
    $acc->email = $_POST['email'];
    $acc->password = $_POST['password'];
    $res = $acc->getUser();
    $user = $res->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['logged'] = "1";
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        header('location:index.php');
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<h2>Login</h2>
<?php if($error) echo "<p style='color:red'>$error</p>"; ?>
<form method="POST">
    <label>Email:</label>
    <input type="email" name="email" required>
    <label>Password:</label>
    <input type="password" name="password" required>
    <button type="submit" name="login_btn" class="btn btn-primary">Login</button>
</form>

</div>
</body>
</html>
