<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pet Clinic</title>
</head>
<body>
    <h2>Welcome to the Pet Clinic</h2>
    <p>You are logged in as <?php echo $_SESSION['user_type']; ?>.</p>

    <?php if ($_SESSION['user_type'] === 'admin'): ?>
        <a href="register.php">Register a new user</a><br>
        <a href="edit_registration.php">Edit a Registration</a><br>
        <a href="edit_bill.php">Edit a Bill</a><br>
        <a href="cancel_bill.php">Cancel a Bill</a><br>
    <?php endif; ?>
    <a href="pet_registration.php">Register a new pet</a><br>
    <a href="record_consultation.php">Record a Consultation</a><br>
    <a href="generate_bill.php">Generate a Bill</a><br>
    <a href="view_bill.php">View a Bill</a><br>

    <a href="logout.php">Logout</a>
</body>
</html>
