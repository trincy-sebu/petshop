<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $RegNo = $_POST['RegNo'];
    $user_id = $_SESSION['user_id'];
    $consultation_date = date('Y-m-d');
    $diagnosis = $_POST['diagnosis'];
    $notes = $_POST['notes'];

    $query = "INSERT INTO consultations (RegNo, user_id, consultation_date, diagnosis, notes)
              VALUES ('$RegNo', '$user_id', '$consultation_date', '$diagnosis', '$notes')";

    if (mysqli_query($conn, $query)) {
        $message = "Consultation recorded successfully.";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Record Consultation</title>
</head>
<body>
    <h2>Record Consultation</h2>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form method="POST" action="record_consultation.php">
        <label for="RegNo">Registration No:</label>
        <input type="text" id="RegNo" name="RegNo" required><br><br>

        <label for="diagnosis">Diagnosis:</label>
        <textarea id="diagnosis" name="diagnosis" required></textarea><br><br>

        <label for="notes">Notes:</label>
        <textarea id="notes" name="notes"></textarea><br><br>

        <input type="submit" value="Record Consultation">
    </form>
</body>
</html>
