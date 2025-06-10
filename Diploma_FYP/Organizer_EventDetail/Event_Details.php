<?php
session_start();
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$total_joined = 0;
$total_likes = 0;

// Assuming you get the eventId from a GET request
$eventId = isset($_GET['eventId']) ? $_GET['eventId'] : null;

if ($eventId === null) {
    die("Event ID is not provided");
}



// Query to get the count of users who joined the event
$sql_joined = "SELECT COUNT(*) as total_joined FROM `order_n` WHERE Event_ID = ?";
$stmt_joined = $conn->prepare($sql_joined);
if (!$stmt_joined) {
    die("Prepare failed for total joined: " . $conn->error);
}
$stmt_joined->bind_param("i", $eventId);
$stmt_joined->execute();
$result_joined = $stmt_joined->get_result();
if ($result_joined) {
    $row_joined = $result_joined->fetch_assoc();
    $total_joined = $row_joined['total_joined'];
} else {
    die("Execution failed for total joined: " . $stmt_joined->error);
}
$stmt_joined->close();

// Query to get the count of user likes
$sql_likes = "SELECT COUNT(*) as total_likes FROM save_event WHERE Event_ID = ?";
$stmt_likes = $conn->prepare($sql_likes);
if (!$stmt_likes) {
    die("Prepare failed for total likes: " . $conn->error);
}
$stmt_likes->bind_param("i", $eventId);
$stmt_likes->execute();
$result_likes = $stmt_likes->get_result();
if ($result_likes) {
    $row_likes = $result_likes->fetch_assoc();
    $total_likes = $row_likes['total_likes'];
} else {
    die("Execution failed for total likes: " . $stmt_likes->error);
}
$stmt_likes->close();

// Query to get purchased users data
$sql_purchased_users = "SELECT u.User_Name, u.User_Contact,u.User_Email, o.Total_Price, o.Order_Date, o.Order_Time
                        FROM `order_n` o
                        INNER JOIN user u ON o.User_ID = u.User_ID
                        WHERE o.Event_Id = ?";
$stmt_purchased_users = $conn->prepare($sql_purchased_users);
if (!$stmt_purchased_users) {
    die("Prepare failed for purchased users: " . $conn->error);
}
$stmt_purchased_users->bind_param("i", $eventId);
$stmt_purchased_users->execute();
$result_purchased_users = $stmt_purchased_users->get_result();

$purchased_users = [];
while ($row = $result_purchased_users->fetch_assoc()) {
    $purchased_users[] = $row;
}
$stmt_purchased_users->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Event_Details.css">
    <?php include 'organizer_header.php'; ?>
</head>
<body>
    
    <div class="container">
        <div class="main-content">
            <h1>Analysis</h1>
            <section class="content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h2>Total People Joined</h2>
                        <p><?php echo $total_joined; ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Total User Likes</h2>
                        <p><?php echo $total_likes; ?></p>
                    </div>
                </div>
            </section>

            <div class="tablecon">
                <h2>Joined User Data</h2>
                <?php if (count($purchased_users) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                                <th>Order Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchased_users as $user): ?>
                                <tr>
                                    <td><?php echo $user['User_Name']; ?></td>
                                    <td><?php echo $user['User_Contact']; ?></td>
                                    <td><?php echo $user['User_Email']; ?></td>
                                    <td><?php echo $user['Total_Price']; ?></td>
                                    <td><?php echo $user['Order_Date']; ?></td>
                                    <td><?php echo $user['Order_Time']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No users have purchased this event.</p>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</body>
</html>
