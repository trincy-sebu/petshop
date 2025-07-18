<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function generateBillId($conn) {
    $current_year = date('Y');
    $query = "SELECT bill_id FROM bills WHERE bill_id LIKE '$current_year-%' ORDER BY bill_id DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $lastBillId = mysqli_fetch_assoc($result)['bill_id'];
        $last_seq_id = (int)substr($lastBillId, strpos($lastBillId, '-') + 1);
        $new_seq_id = $last_seq_id + 1;
    } else {
        $new_seq_id = 1;
    }

    return $current_year . '-' . str_pad($new_seq_id, 4, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bill_id = generateBillId($conn);
    $consultation_id = $_POST['consultation_id'];
    $RegNo = $_POST['RegNo'];
    $bill_date = date('Y-m-d');
    $total_amount = 0;
    $status = 'pending';
    $recorded_by_user_id = $_SESSION['user_id'];

    // Calculate total amount
    if (isset($_POST['items'])) {
        foreach ($_POST['items'] as $item) {
            $quantity = (int)$item['quantity'];
            $unit_price = (float)$item['unit_price'];
            $subtotal = $quantity * $unit_price;
            $total_amount += $subtotal;
        }
    }

    $query = "INSERT INTO bills (bill_id, consultation_id, RegNo, bill_date, total_amount, status, recorded_by_user_id)
              VALUES ('$bill_id', '$consultation_id', '$RegNo', '$bill_date', '$total_amount', '$status', '$recorded_by_user_id')";

    if (mysqli_query($conn, $query)) {
        if (isset($_POST['items'])) {
            foreach ($_POST['items'] as $item) {
                $item_description = $item['description'];
                $quantity = (int)$item['quantity'];
                $unit_price = (float)$item['unit_price'];
                $subtotal = $quantity * $unit_price;

                $item_query = "INSERT INTO bill_items (bill_id, item_description, quantity, unit_price, subtotal)
                               VALUES ('$bill_id', '$item_description', '$quantity', '$unit_price', '$subtotal')";
                mysqli_query($conn, $item_query);
            }
        }
        $message = "Bill generated successfully with Bill ID: $bill_id";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Generate Bill</title>
    <script>
        function addItem() {
            var itemsDiv = document.getElementById('items');
            var itemIndex = itemsDiv.getElementsByClassName('item').length;
            var newItem = document.createElement('div');
            newItem.className = 'item';
            newItem.innerHTML = `
                <hr>
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
    <h2>Generate Bill</h2>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form method="POST" action="generate_bill.php">
        <label for="RegNo">Registration No:</label>
        <input type="text" id="RegNo" name="RegNo" required><br><br>

        <label for="consultation_id">Consultation ID:</label>
        <input type="text" id="consultation_id" name="consultation_id" required><br><br>

        <h3>Bill Items</h3>
        <div id="items">
            <div class="item">
                <label>Item Description:</label>
                <input type="text" name="items[0][description]" required>
                <label>Quantity:</label>
                <input type="number" name="items[0][quantity]" required>
                <label>Unit Price:</label>
                <input type="text" name="items[0][unit_price]" required>
            </div>
        </div>
        <button type="button" onclick="addItem()">Add Another Item</button><br><br>

        <input type="submit" value="Generate Bill">
    </form>
</body>
</html>
