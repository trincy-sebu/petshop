<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$bill_details = null;
$bill_items = [];

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    // Get bill details
    $bill_query = "SELECT b.*, r.owner_name, r.pet_name FROM bills b
                   JOIN registrations r ON b.RegNo = r.RegNo
                   WHERE b.bill_id = '$bill_id'";
    $bill_result = mysqli_query($conn, $bill_query);
    if ($bill_result && mysqli_num_rows($bill_result) > 0) {
        $bill_details = mysqli_fetch_assoc($bill_result);
    }

    // Get bill items
    $items_query = "SELECT * FROM bill_items WHERE bill_id = '$bill_id'";
    $items_result = mysqli_query($conn, $items_query);
    if ($items_result && mysqli_num_rows($items_result) > 0) {
        while ($row = mysqli_fetch_assoc($items_result)) {
            $bill_items[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Bill</title>
</head>
<body>
    <h2>View Bill</h2>

    <form method="GET" action="view_bill.php">
        <label for="bill_id">Enter Bill ID:</label>
        <input type="text" id="bill_id" name="bill_id" required>
        <input type="submit" value="View Bill">
    </form>

    <?php if ($bill_details): ?>
        <h3>Bill Details</h3>
        <p><strong>Bill ID:</strong> <?php echo $bill_details['bill_id']; ?></p>
        <p><strong>Registration No:</strong> <?php echo $bill_details['RegNo']; ?></p>
        <p><strong>Owner Name:</strong> <?php echo $bill_details['owner_name']; ?></p>
        <p><strong>Pet Name:</strong> <?php echo $bill_details['pet_name']; ?></p>
        <p><strong>Bill Date:</strong> <?php echo $bill_details['bill_date']; ?></p>
        <p><strong>Total Amount:</strong> <?php echo $bill_details['total_amount']; ?></p>
        <p><strong>Status:</strong> <?php echo $bill_details['status']; ?></p>

        <h3>Bill Items</h3>
        <table border="1">
            <tr>
                <th>Item Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($bill_items as $item): ?>
            <tr>
                <td><?php echo $item['item_description']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['unit_price']; ?></td>
                <td><?php echo $item['subtotal']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (isset($_GET['bill_id'])): ?>
        <p>No bill found with the specified ID.</p>
    <?php endif; ?>
</body>
</html>
