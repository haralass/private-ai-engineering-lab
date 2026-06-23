<?php
session_start();
$error = $_SESSION['login_error'] ?? "";
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>

<h2>Login</h2>

<?php if (!empty($error)): ?>
    <div style="color:red;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="login_processor.php">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit" name="login">Log In</button>
</form>

</body>
</html>
