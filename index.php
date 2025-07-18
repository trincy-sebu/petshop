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
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Pet Clinic</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Welcome to the Pet Clinic</h2>
        <p>You are logged in as <?php echo $_SESSION['user_type']; ?>.</p>

        <div class="menu-container">
            <div class="menu-card">
                <h3>Staff Menu</h3>
                <ul>
                    <li><a href="pet_registration.php">Register a new pet</a></li>
                    <li><a href="record_consultation.php">Record a Consultation</a></li>
                    <li><a href="generate_bill.php">Generate a Bill</a></li>
                    <li><a href="view_bill.php">View a Bill</a></li>
                </ul>
            </div>

            <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <div class="menu-card">
                <h3>Admin Menu</h3>
                <ul>
                    <li><a href="register.php">Register a new user</a></li>
                    <li><a href="edit_registration.php">Edit a Registration</a></li>
                    <li><a href="edit_bill.php">Edit a Bill</a></li>
                    <li><a href="cancel_bill.php">Cancel a Bill</a></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
