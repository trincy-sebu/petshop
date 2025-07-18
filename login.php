<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Please enter both username and password.";
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }

    if(isset($username)) {
        $stmt = mysqli_prepare($conn, "SELECT user_id, password, user_type FROM users WHERE username = ?");
        if ($stmt === false) {
            $error = "Prepare failed: " . htmlspecialchars(mysqli_error($conn));
        } else {
            mysqli_stmt_bind_param($stmt, "s", $username);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if ($result && mysqli_num_rows($result) > 0) {
                    $user = mysqli_fetch_assoc($result);
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['user_type'] = $user['user_type'];
                        header("Location: index.php");
                        exit();
                    } else {
                        $error = "Invalid password.";
                    }
                } else {
                    $error = "No user found with that username.";
                }
            } else {
                $error = "Execute failed: " . htmlspecialchars(mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
