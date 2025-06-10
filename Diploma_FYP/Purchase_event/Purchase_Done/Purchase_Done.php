<?php
include 'db.php';
session_start();

if (!isset($_GET['order_id'])) {
    echo "No order ID provided.";
    exit();
}

$order_id = intval($_GET['order_id']);

// Update the order with the current date and time
date_default_timezone_set('Asia/Kuala_Lumpur');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');

// Prepare the update SQL statement
$update_sql = "UPDATE `order_n` SET Order_Date = ?, Order_Time = ? WHERE Order_Id = ?";
$update_stmt = $conn->prepare($update_sql);
if (!$update_stmt) {
    echo "Error preparing update statement: " . $conn->error;
    exit();
}
$update_stmt->bind_param("ssi", $current_date, $current_time, $order_id);
$update_stmt->execute();
$update_stmt->close();

// Join user, event, and order tables to fetch the necessary data
$sql = "
    SELECT 
        u.User_Name AS User_Name,  -- Assuming 'username' is a valid column in 'user' table
        u.User_Email AS User_Email,
        e.Event_Title,
        e.Event_Date,
        e.Event_Time,
        e.Event_Location,
        e.Event_State,
        o.order_id
    FROM `order_n` o
    JOIN user u ON o.User_Id = u.User_Id
    JOIN event e ON o.Event_Id = e.Event_Id
    WHERE o.Order_Id = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "Order not found.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
    /* Styles as provided before */
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

    .container {
        background-color: #fff;
        margin: 20px auto;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 600px;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .header .check-mark {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .header .check-mark svg {
        width: 30px;
        height: 30px;
        fill: #4CAF50;
    }

    .header .order-number {
        font-size: 14px;
        color: #666;
    }

    .section-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .event-details {
        margin-bottom: 20px;
    }

    .contact-info {
        margin-bottom: 20px;
    }

    .contact-info label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .contact-info input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 10px;
    }

    .buttons {
    display: flex;
    justify-content: space-between;
}

.buttons a {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none; /* Ensure link looks like a button */
}

.done-button {
    background-color: #4CAF50;
    color: #fff;
}

    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="check-mark">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
            </svg>
            Thank you for your order!
        </div>
        <div class="order-number">#<?php echo htmlspecialchars($order['order_id']); ?></div>
    </div>

    <div class="section-title">YOU'RE GOING TO</div>

    <div class="section-title"><?php echo htmlspecialchars($order['Event_Title']); ?></div>

    <div class="event-details">
        Order #<?php echo htmlspecialchars($order['order_id']); ?> on <?php echo date('l, F j, g:i A'); ?>
        <br>
        Event information: <?php echo date('l, F j', strtotime($order['Event_Date'])); ?>
        <br>
        From <?php echo date('g:i A', strtotime($order['Event_Time'])); ?>
        <br>
        (Malaysia Time Malaysia (Kuala Lumpur) Time)
        <br>
        <?php echo htmlspecialchars($order['Event_Location']) .','. htmlspecialchars($order['Event_State']) ; ?>
    </div>

    <div class="section-title">Contact Information</div>

    <div class="contact-info">
        <label for="firstName">Full Name </label>
        <input type="text" id="firstName" value="<?php echo htmlspecialchars($order['User_Name']); ?>" disabled>

        <label for="email">Email </label>
        <input type="email" id="email" value="<?php echo htmlspecialchars($order['User_Email']); ?>" disabled>
    </div>

    <div class="buttons">
        <a href="http://localhost/FINAL_FYP/HomePage/HomePage.php" class="done-button">Done</a>
    </div>
</div>

</body>
</html>