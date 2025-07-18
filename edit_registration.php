<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$reg_details = null;

if (isset($_GET['RegNo'])) {
    $RegNo = $_GET['RegNo'];
    $query = "SELECT * FROM registrations WHERE RegNo = '$RegNo'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $reg_details = mysqli_fetch_assoc($result);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $RegNo = $_POST['RegNo'];
    $owner_name = $_POST['owner_name'];
    $owner_address = $_POST['owner_address'];
    $owner_phone = $_POST['owner_phone'];
    $owner_email = $_POST['owner_email'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];

    $update_query = "UPDATE registrations SET
                        owner_name = '$owner_name',
                        owner_address = '$owner_address',
                        owner_phone = '$owner_phone',
                        owner_email = '$owner_email',
                        pet_name = '$pet_name',
                        species = '$species',
                        breed = '$breed',
                        age = '$age'
                    WHERE RegNo = '$RegNo'";

    if (mysqli_query($conn, $update_query)) {
        $message = "Registration updated successfully.";
        // Refresh data to show updated values
        header("Location: edit_registration.php?RegNo=$RegNo&message=$message");
        exit();
    } else {
        $error = "Error updating registration: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Edit Registration</h2>

        <form method="GET" action="edit_registration.php">
            <label for="RegNo">Enter Registration No:</label>
            <input type="text" id="RegNo" name="RegNo" required value="<?php echo isset($_GET['RegNo']) ? $_GET['RegNo'] : ''; ?>">
            <input type="submit" value="Fetch Registration">
        </form>

        <?php if (isset($_GET['message'])) { echo "<p class='message'>".$_GET['message']."</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <?php if ($reg_details): ?>
        <form method="POST" action="edit_registration.php">
            <input type="hidden" name="RegNo" value="<?php echo $reg_details['RegNo']; ?>">

            <label for="owner_name">Owner Name:</label>
            <input type="text" id="owner_name" name="owner_name" value="<?php echo $reg_details['owner_name']; ?>" required>

            <label for="owner_address">Owner Address:</label>
            <textarea id="owner_address" name="owner_address" required><?php echo $reg_details['owner_address']; ?></textarea>

            <label for="owner_phone">Owner Phone:</label>
            <input type="text" id="owner_phone" name="owner_phone" value="<?php echo $reg_details['owner_phone']; ?>" required>

            <label for="owner_email">Owner Email:</label>
            <input type="email" id="owner_email" name="owner_email" value="<?php echo $reg_details['owner_email']; ?>" required>

            <hr>

            <label for="pet_name">Pet Name:</label>
            <input type="text" id="pet_name" name="pet_name" value="<?php echo $reg_details['pet_name']; ?>" required>

            <label for="species">Species:</label>
            <input type="text" id="species" name="species" value="<?php echo $reg_details['species']; ?>" required>

            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed" value="<?php echo $reg_details['breed']; ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo $reg_details['age']; ?>" required>

            <input type="submit" value="Update Registration">
        </form>
        <?php elseif (isset($_GET['RegNo'])): ?>
            <p class="error">No registration found with the specified Registration No.</p>
        <?php endif; ?>
    </div>
</body>
</html>
