<?php
session_start();

require_once '../db.php'; // Adjust path as per your file structure

// Check if event ID is provided in the URL
if (isset($_GET['eventId'])) {
    $eventId = $_GET['eventId'];

    // Fetch event details including reject reason
    $sql = "SELECT e.Event_R_Id, e.Event_R_Title, e.Event_R_Venue, e.Event_R_Time, e.Event_R_AdultTicket, e.Event_R_Date, e.Event_R_Status, e.Reject_Reason
            FROM event_request e
            WHERE e.Event_R_Id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
        // Assuming the event status is 'reject' to display the reject reason
        if ($event['Event_R_Status'] == 'reject') {
            $rejectReason = $event['Reject_Reason'];
        } else {
            // Handle case where status is not 'reject' if necessary
            $rejectReason = "Event status does not indicate rejection.";
        }
    } else {
        // Handle case where event with given ID is not found
        $rejectReason = "Event not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle case where event ID is not provided in the URL
    $rejectReason = "Event ID not specified.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejected Event Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f0f3f5;
            margin: 0;
            padding: 20px;
        }
        .event-details {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .event-details h2 {
            font-size: 1.5em;
            color: #ff661f;
            margin-bottom: 10px;
        }
        .event-details p {
            margin-bottom: 15px;
        }
        .contact-us {
            text-align: center;
        }
        .contact-us a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff661f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        .contact-us a:hover {
            background-color: #e44b06;
        }
    </style>
</head>
<body>
    <?php include 'organizer_header.php'?>
    <div class="event-details">
        <h2><?php echo htmlspecialchars($event['Event_R_Title']); ?></h2>
        <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['Event_R_Venue']); ?></p>
        <p><strong>Date:</strong> <?php echo date('M d, Y', strtotime($event['Event_R_Date'])); ?></p>
        <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($event['Event_R_Time'])); ?></p>
        <p><strong>Reject Reason:</strong> <?php echo htmlspecialchars($rejectReason); ?></p>
    </div>
    <div class="contact-us">
        <a href="../Organizer_Customer_Services/ContactUs.php">Contact Us</a>
    </div>
</body>
</html>
