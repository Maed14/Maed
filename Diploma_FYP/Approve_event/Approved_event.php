<?php
session_start();
require_once 'db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$eventId = isset($_GET['Event_Id']) ? intval($_GET['Event_Id']) : 1;

if ($eventId < 1) {
    die("Invalid event ID");
}

$sql = "SELECT * FROM event WHERE Event_Id = ?";
$getEventStmt = $conn->prepare($sql);
$getEventStmt->bind_param("i", $eventId);
$getEventStmt->execute();
$result = $getEventStmt->get_result();
$event = $result->fetch_assoc();

if (!$event) {
    die("Event not found");
}


$prevEventId = 0;
$nextEventId = 0;


$prevSql = "SELECT Event_Id FROM event WHERE Event_Id < ? ORDER BY Event_Id DESC LIMIT 1";
$prevStmt = $conn->prepare($prevSql);
if ($prevStmt) {
    $prevStmt->bind_param("i", $eventId);
    $prevStmt->execute();
    $prevResult = $prevStmt->get_result();
    if ($prevResult->num_rows > 0) {
        $prevEvent = $prevResult->fetch_assoc();
        $prevEventId = $prevEvent['Event_Id'];
    }
    $prevStmt->close();
}


$nextSql = "SELECT Event_Id FROM event WHERE Event_Id > ? ORDER BY Event_Id ASC LIMIT 1";
$nextStmt = $conn->prepare($nextSql);
if ($nextStmt) {
    $nextStmt->bind_param("i", $eventId);
    $nextStmt->execute();
    $nextResult = $nextStmt->get_result();
    if ($nextResult->num_rows > 0) {
        $nextEvent = $nextResult->fetch_assoc();
        $nextEventId = $nextEvent['Event_Id'];
    }
    $nextStmt->close();
}

$conn->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Approved_event.css">
    <title>Approved Event</title>
</head>
<body>
   
    <div class="content">
        <h1 style="font-size: 50px; text-align: center; color: #FF5106;">Approved Event</h1>
    
        <div class="container">
            <div class="form-group">
                <label for="name">Personal Name / Company Name:</label>
                <input type="text" class="text" id="name" name="name" value="<?php echo htmlspecialchars($event['Organizer_Name']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="contact">Contact Number:</label>
                <input type="text" class="text" id="contact" name="contact" value="<?php echo htmlspecialchars($event['Organizer_Contact']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($event['Organizer_Email']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="eventName">Event Name:</label>
                <input type="text" class="text" id="eventName" name="eventName" value="<?php echo htmlspecialchars($event['Event_Title']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="category">Event Category:</label>
                <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($event['Event_Category']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="eventDate">Event Date:</label>
                <input type="text" class="text" id="eventDate" name="eventDate" value="<?php echo date('Y-m-d', strtotime($event['Event_Date'])); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="eventTime">Event Time:</label>
                <input type="text" class="text" id="eventTime" name="eventTime" value="<?php echo date('H:i', strtotime($event['Event_Time'])); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="ticketPriceAdult">Ticket Price (Adult):</label>
                <input type="number" id="ticketPriceAdult" name="ticketPriceAdult" value="<?php echo htmlspecialchars($event['Adult_Ticket']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="capacity">Participants Capacity:</label>
                <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($event['Event_Capacity']); ?>" disabled>
            </div>

            <div class="form-group">
                <label for="details">Event Details:</label>
                <textarea id="details" name="details" disabled><?php echo htmlspecialchars($event['Event_Description']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="picture">Picture Provided:</label>
                <img src="<?php echo '../Create_event/img/' . htmlspecialchars($event['Event_Picture']); ?>" alt="Event Picture" style="max-width: 300px;">
            </div>

            <div class="next-buttons">
                <?php if ($prevEventId > 0): ?>
                    <a href="Approved_event.php?Event_Id=<?php echo $prevEventId; ?>" class="button">Previous</a>
                <?php endif; ?>

                <?php if ($nextEventId > 0): ?>
                    <a href="Approved_event.php?Event_Id=<?php echo $nextEventId; ?>" class="button">Next</a>
                <?php endif; ?>
            </div>
                        
        </div>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
