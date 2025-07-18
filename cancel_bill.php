<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_id = $_POST['bill_id'];

    $update_query = "UPDATE bills SET status = 'cancelled' WHERE bill_id = '$bill_id'";
    if (mysqli_query($conn, $update_query)) {
        $message = "Bill cancelled successfully.";
    } else {
        $error = "Error cancelling bill: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cancel Bill</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Cancel Bill</h2>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="cancel_bill.php">
            <label for="bill_id">Enter Bill ID to Cancel:</label>
            <input type="text" id="bill_id" name="bill_id" required>
            <input type="submit" value="Cancel Bill">
        </form>
    </div>
</body>
</html>
