<?php
    include 'db.php';
    session_start();

    if (!isset($_GET['order_id'])) {
        echo "No order ID provided.";
        exit();
    }

    $order_id = intval($_GET['order_id']);

    $sql = "
        SELECT 
            u.User_Name AS User_Name, 
            u.User_Email AS User_Email,
            e.Event_Title,
            e.Event_Date,
            e.Event_Time,
            e.Event_Location,
            o.Order_Id,
            o.Order_Date,  
            o.Order_Time   
        FROM `order_n` o
        JOIN user u ON o.user_id = u.User_Id
        JOIN event e ON o.event_id = e.Event_ID
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            margin: 20px auto;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
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
        <div class="order-number">#<?php echo htmlspecialchars($order['Order_Id']); ?></div>
    </div>

    <div class="section-title">YOU'RE GOING TO</div>

    <div class="section-title"><?php echo htmlspecialchars($order['Event_Title']); ?></div>

    <div class="event-details">
        Order #<?php echo htmlspecialchars($order['Order_Id']); ?> on <?php echo date('l, F j, g:i A', strtotime($order['Order_Date'] . ' ' . $order['Order_Time'])); ?>
        <br>
        Event information: <?php echo date('l, F j', strtotime($order['Event_Date'])); ?>
        <br>
        From <?php echo date('g:i A', strtotime($order['Event_Time'])); ?>
        <br>
        <?php echo htmlspecialchars($order['Event_Location']); ?>
    </div>

    <div class="section-title">Contact Information</div>

    <div class="contact-info">
        <label for="firstName">Full Name </label>
        <input type="text" id="firstName" value="<?php echo htmlspecialchars($order['User_Name']); ?>" readonly>

        <label for="email">Email </label>
        <input type="email" id="email" value="<?php echo htmlspecialchars($order['User_Email']); ?>" readonly>
    </div>

    
    <div class="buttons">
        <a href="http://localhost/FINAL_FYP/HomePage/HomePage.php" class="done-button">Done</a>
    </div>
    
</div>

</body>
</html>
