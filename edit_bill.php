<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$bill_details = null;
$bill_items = [];

if (isset($_GET['bill_id'])) {
    $bill_id = $_GET['bill_id'];

    // Get bill details
    $bill_query = "SELECT * FROM bills WHERE bill_id = '$bill_id'";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_id = $_POST['bill_id'];
    $status = $_POST['status'];
    $total_amount = 0;

    // Update bill items and calculate total amount
    if (isset($_POST['items'])) {
        // First, clear existing items for this bill
        $delete_items_query = "DELETE FROM bill_items WHERE bill_id = '$bill_id'";
        mysqli_query($conn, $delete_items_query);

        foreach ($_POST['items'] as $item) {
            $item_description = $item['description'];
            $quantity = (int)$item['quantity'];
            $unit_price = (float)$item['unit_price'];
            $subtotal = $quantity * $unit_price;
            $total_amount += $subtotal;

            $item_query = "INSERT INTO bill_items (bill_id, item_description, quantity, unit_price, subtotal)
                           VALUES ('$bill_id', '$item_description', '$quantity', '$unit_price', '$subtotal')";
            mysqli_query($conn, $item_query);
        }
    }

    $update_query = "UPDATE bills SET status = '$status', total_amount = '$total_amount' WHERE bill_id = '$bill_id'";
    if (mysqli_query($conn, $update_query)) {
        $message = "Bill updated successfully.";
        // Refresh data to show updated values
        header("Location: edit_bill.php?bill_id=$bill_id&message=$message");
        exit();

    } else {
        $error = "Error updating bill: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Bill</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script>
        function addItem() {
            var itemsDiv = document.getElementById('items');
            var itemIndex = itemsDiv.getElementsByClassName('item').length;
            var newItem = document.createElement('div');
            newItem.className = 'item';
            newItem.innerHTML = `
                <label>Item Description:</label>
                <input type="text" name="items[${itemIndex}][description]" required>
                <label>Quantity:</label>
                <input type="number" name="items[${itemIndex}][quantity]" required>
                <label>Unit Price:</label>
                <input type="text" name="items[${itemIndex}][unit_price]" required>
            `;
            itemsDiv.appendChild(newItem);
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Bill</h2>

        <form method="GET" action="edit_bill.php">
            <label for="bill_id">Enter Bill ID:</label>
            <input type="text" id="bill_id" name="bill_id" required value="<?php echo isset($_GET['bill_id']) ? $_GET['bill_id'] : ''; ?>">
            <input type="submit" value="Fetch Bill">
        </form>

        <?php if (isset($_GET['message'])) { echo "<p class='message'>".$_GET['message']."</p>"; } ?>
        <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

        <?php if ($bill_details): ?>
        <form method="POST" action="edit_bill.php">
            <input type="hidden" name="bill_id" value="<?php echo $bill_details['bill_id']; ?>">

            <p><strong>Bill ID:</strong> <?php echo $bill_details['bill_id']; ?></p>
            <p><strong>Registration No:</strong> <?php echo $bill_details['RegNo']; ?></p>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="pending" <?php if($bill_details['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="paid" <?php if($bill_details['status'] == 'paid') echo 'selected'; ?>>Paid</option>
                <option value="cancelled" <?php if($bill_details['status'] == 'cancelled') echo 'selected'; ?>>Cancelled</option>
            </select>

            <h3>Bill Items</h3>
            <div id="items">
                <?php foreach ($bill_items as $index => $item): ?>
                <div class="item">
                    <label>Item Description:</label>
                    <input type="text" name="items[<?php echo $index; ?>][description]" value="<?php echo $item['item_description']; ?>" required>
                    <label>Quantity:</label>
                    <input type="number" name="items[<?php echo $index; ?>][quantity]" value="<?php echo $item['quantity']; ?>" required>
                    <label>Unit Price:</label>
                    <input type="text" name="items[<?php echo $index; ?>][unit_price]" value="<?php echo $item['unit_price']; ?>" required>
                </div>
                <?php endforeach; ?>
            </div>
            <button type="button" onclick="addItem()">Add Another Item</button>

            <input type="submit" value="Update Bill">
        </form>
        <?php elseif (isset($_GET['bill_id'])): ?>
            <p class="error">No bill found with the specified ID.</p>
        <?php endif; ?>
    </div>
</body>
</html>
