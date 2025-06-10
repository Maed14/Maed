<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="YourOrder.css">
    <title>Your orders</title>
</head>

<body>

    <?php

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        include '../user_header.php';

        require_once 'db.php'; 

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $userId = $_SESSION['user_id'] ?? 0; 

        $sql = "SELECT o.Order_Id, o.Event_Id, e.Event_Title, e.Event_Location, e.Event_Time, o.Total_Price, e.Event_Date
                FROM `order_n` o
                INNER JOIN event e ON o.Event_Id = e.Event_Id
                WHERE o.User_Id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
    ?>
    
    <div class="content">
        <div class="ordersheader">
            <h1 style="font-size: 40px; color: #FF5106;">Your orders</h1>
        </div>
        
        <div class="order-list">
            <?php
            
            $currentDate = date('Y-m-d');

            while ($row = $result->fetch_assoc()) {
                
                $eventDate = date('Y-m-d', strtotime($row['Event_Date']));
                if ($eventDate < $currentDate) {
                    $eventStatus = "Ended";
                } elseif ($eventDate == $currentDate) {
                    $eventStatus = "On Going";
                } else {
                    $eventStatus = "Coming Soon";
                }
                ?>
                <div class="order-item">
                    <div class="order-item-date">
                        <span class="day"><?php echo date('d', strtotime($row['Event_Date'])); ?></span>
                        <span class="month"><?php echo date('M', strtotime($row['Event_Date'])); ?></span>
                    </div>

                    <div class="order-item-content">
                        <div class="event-header">
                            <strong><?php echo htmlspecialchars($row['Event_Title']); ?></strong> 
                            <span class="event_status"><?php echo $eventStatus; ?></span>
                        </div>
                        <span><?php echo htmlspecialchars($row['Event_Location']); ?></span>
                        <span><?php echo date('h:i A', strtotime($row['Event_Time'])); ?></span>
                        <span>Price: RM<?php echo htmlspecialchars($row['Total_Price']); ?></span>
                    </div>



                    <div class="details">
                <a href="Order_Details.php?order_id=<?php echo $row['Order_Id'];?>">Details</a>
            </div>
                </div>
                <?php
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>
