<?php
session_start();
include '../db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all rejected events
$sql = "SELECT Event_R_Id, Organizer_Name, Organizer_Contact, Organizer_Email, Event_R_Title, Event_R_Date, Event_R_Venue, Reject_Reason FROM event_request WHERE Event_R_Status = 'reject'";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejected Events</title>
    <link rel="stylesheet" href="Admin_RejectedEvent.css">
</head>
<body>
    
    <div class="container">
        <h1>Rejected Events</h1>
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="event">
                    <h2><?php echo htmlspecialchars($event['Event_R_Title']); ?></h2>
                    <p><strong>Date:</strong> <?php echo htmlspecialchars($event['Event_R_Date']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($event['Event_R_Venue']); ?></p>
                    <p><strong>Organizer Name:</strong> <?php echo htmlspecialchars($event['Organizer_Name']); ?></p>
                    <p><strong>Organizer Contact:</strong> <?php echo htmlspecialchars($event['Organizer_Contact']); ?></p>
                    <p><strong>Organizer Email:</strong> <?php echo htmlspecialchars($event['Organizer_Email']); ?></p>
                    <p><strong>Rejection Reason:</strong> <?php echo htmlspecialchars($event['Reject_Reason']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No rejected events found.</p>
        <?php endif; ?>
    </div>
    <?php include '../footer.php'; ?>
</body>
</html>
