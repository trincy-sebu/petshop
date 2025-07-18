<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function generateRegNo($conn) {
    $current_year = date('Y');
    $query = "SELECT RegNo FROM registrations WHERE RegNo LIKE '%-$current_year' ORDER BY RegId DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $lastRegNo = mysqli_fetch_assoc($result)['RegNo'];
        $last_seq_id = (int)substr($lastRegNo, 0, strpos($lastRegNo, '-'));
        $new_seq_id = $last_seq_id + 1;
    } else {
        $new_seq_id = 1000;
    }

    return "$new_seq_id-$current_year";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $RegNo = generateRegNo($conn);
    $owner_name = $_POST['owner_name'];
    $owner_address = $_POST['owner_address'];
    $owner_phone = $_POST['owner_phone'];
    $owner_email = $_POST['owner_email'];
    $pet_name = $_POST['pet_name'];
    $species = $_POST['species'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $registration_date = date('Y-m-d');

    $query = "INSERT INTO registrations (RegNo, owner_name, owner_address, owner_phone, owner_email, pet_name, species, breed, age, registration_date)
              VALUES ('$RegNo', '$owner_name', '$owner_address', '$owner_phone', '$owner_email', '$pet_name', '$species', '$breed', '$age', '$registration_date')";

    if (mysqli_query($conn, $query)) {
        $message = "Pet registered successfully with Registration No: $RegNo";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Owner and Pet Registration</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Owner and Pet Registration</h2>
        <?php if (isset($message)) { echo "<p class='message'>$message</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
        <form method="POST" action="pet_registration.php">
            <label for="owner_name">Owner Name:</label>
            <input type="text" id="owner_name" name="owner_name" required>

            <label for="owner_address">Owner Address:</label>
            <textarea id="owner_address" name="owner_address" required></textarea>

            <label for="owner_phone">Owner Phone:</label>
            <input type="text" id="owner_phone" name="owner_phone" required>

            <label for="owner_email">Owner Email:</label>
            <input type="email" id="owner_email" name="owner_email" required>

            <hr>

            <label for="pet_name">Pet Name:</label>
            <input type="text" id="pet_name" name="pet_name" required>

            <label for="species">Species:</label>
            <input type="text" id="species" name="species" required>

            <label for="breed">Breed:</label>
            <input type="text" id="breed" name="breed" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>
