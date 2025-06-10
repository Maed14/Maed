<?php
session_start();
require_once '../db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;

if ($userId < 1) {
    die("Invalid user ID");
}

// Fetch user details
$getUserSql = "SELECT * FROM user WHERE User_Id = ?";
$getUserStmt = $conn->prepare($getUserSql);

if (!$getUserStmt) {
    die("Prepare statement failed: " . $conn->error);
}

$getUserStmt->bind_param("i", $userId);
$getUserStmt->execute();
$result = $getUserStmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found");
}

// Fetch events participated by the user
$getEventsSql = "SELECT e.Event_Id, e.Event_Title, e.Event_Date, e.Event_Location, e.Event_Time, e.Adult_Ticket, o.Order_Id
                 FROM event e
                 INNER JOIN `order_n` o ON e.Event_Id = o.Event_Id
                 WHERE o.User_Id = ?";
$getEventsStmt = $conn->prepare($getEventsSql);

if (!$getEventsStmt) {
    die("Prepare statement failed: " . $conn->error);
}

$getEventsStmt->bind_param("i", $userId);
$getEventsStmt->execute();
$eventsResult = $getEventsStmt->get_result();

// Close statements and connection
$getEventsStmt->close();
$getUserStmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="User_details.css">
    <title>User Details</title>
</head>
<body>
    <div class="content">
        <h1 style="font-size: 50px; text-align: center; color: #FF5106;">User Details</h1>
    
        <div class="container">
            <div class="form-group">
                <label for="name">User Name:</label>
                <input type="text" class="text" id="name" name="name" value="<?php echo htmlspecialchars($user['User_Name']); ?>" disabled><br>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['User_Email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" class="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['User_Contact']); ?>" disabled>
            </div>
        </div>

        <div class="event-participation">
            <h2>Events Participated:</h2>
            <ul class="order-list">
                <?php
                $currentDate = date('Y-m-d');
                while ($row = $eventsResult->fetch_assoc()) {
                    $eventDate = date('Y-m-d', strtotime($row['Event_Date']));
                    if ($eventDate < $currentDate) {
                        $eventStatus = "Ended";
                    } elseif ($eventDate == $currentDate) {
                        $eventStatus = "On Going";
                    } else {
                        $eventStatus = "Coming Soon";
                    }
                    ?>
                    <li class="order-item">
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
                            <span>Price: RM<?php echo htmlspecialchars($row['Adult_Ticket']); ?></span>
                        </div>

                        <div class="details">
                            <a href="../YourOrder/Order_Details.php?order_id=<?= $row['Order_Id'];?>">Details</a>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>
