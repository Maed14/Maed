<?php
session_start();
require_once 'db.php'; 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getEventData($conn) {
    $sqlEvent = "SELECT *, Event_R_Photo FROM event_request WHERE Event_R_Status IS NULL";
    $getEventStmt = $conn->prepare($sqlEvent);

    if (!$getEventStmt) {
        die("Prepare failed: " . $conn->error);
    }

    $getEventStmt->execute() or die("Execute failed: " . $getEventStmt->error);

    $result = $getEventStmt->get_result();

    if (!$result) {
        die("Get result failed: " . $getEventStmt->error);
    }

    $events = array();
    while ($event = $result->fetch_assoc()) {
        $events[] = $event;
    }
    $getEventStmt->close();

    return $events;
}

$events = getEventData($conn);

if (empty($events)) {
    $conn->close();
    $message = "No events available for approval.";
    echo "<script>
            alert('$message');
            window.location.href = '../Admin_Page/admin_page.php';
          </script>";
    exit;
}

$eventApproved = false;
$eventRejected = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentEventId = intval($_POST['event_id']);

    if (!is_numeric($currentEventId) || $currentEventId < 1) {
        $conn->close();
        $message = "Invalid event ID";
        echo "<script>alert('$message'); window.location.href = 'event_approval.php';</script>";
        exit;
    }

    if (isset($_POST['approve'])) {
        $conn->begin_transaction();

        try {
            $transferSql = "INSERT INTO event (Organizer_Name, Organizer_Contact, Organizer_Email, Event_Title, Event_Category, Event_Date, Event_Time, Event_Location, Event_State, Adult_Ticket, Event_Capacity, Event_Description, Event_Picture)
                            SELECT Organizer_Name, Organizer_Contact, Organizer_Email, Event_R_Title, Event_R_Category, Event_R_Date, Event_R_Time, Event_R_Venue, Event_R_State, Event_R_AdultTicket, Event_R_Capacity, Event_R_Description, Event_R_Photo
                            FROM event_request WHERE Event_R_Id = ?";
            $transferStmt = $conn->prepare($transferSql);
            $transferStmt->bind_param("i", $currentEventId);
            $transferStmt->execute() or die($conn->error);
            $transferStmt->close();

            $updateSql = "UPDATE event_request SET Event_R_Status = 'approve' WHERE Event_R_Id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("i", $currentEventId);
            $updateStmt->execute() or die($conn->error);
            $updateStmt->close();

            $conn->commit();

            $eventApproved = true;
        } catch (Exception $e) {
            $conn->rollback();
            $conn->close();
            $message = "Error: " . $e->getMessage();
            echo "<script>alert('$message'); window.location.href = 'event_approval.php';</script>";
            exit;
        }
    } elseif (isset($_POST['reject'])) {
        $rejectionReason = $_POST['rejection_reason'];

        $updateSql = "UPDATE event_request SET Event_R_Status = 'reject', Reject_Reason = ? WHERE Event_R_Id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $rejectionReason, $currentEventId);
        $updateStmt->execute() or die($conn->error);
        $updateStmt->close();

        $eventRejected = true;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="EventApproval.css">
    <title>Event Approval</title>
</head>
<body>
    <div class="content">
        <h1 style="font-size: 50px; text-align: center; color: #FF5106;">Event Approval</h1>
        <div class="container">
            <?php foreach ($events as $event): ?>
            <div class="event-details">
                <!-- Display Event Details -->
                <div class="form-group">
                    <label for="eventName">Event Name:</label>
                    <input type="text" class="text" id="eventName" name="eventName" value="<?php echo htmlspecialchars($event['Event_R_Title']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="category">Event Category:</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($event['Event_R_Category']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="eventDate">Event Venue:</label>
                    <input type="text" class="text" id="eventVenue" name="eventVenue" value="<?php echo htmlspecialchars($event['Event_R_Venue']).','.htmlspecialchars($event['Event_R_State']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="eventDate">Event Date:</label>
                    <input type="text" class="text" id="eventDate" name="eventDate" value="<?php echo date('Y-m-d', strtotime($event['Event_R_Date'])); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="eventTime">Event Time:</label>
                    <input type="text" class="text" id="eventTime" name="eventTime" value="<?php echo date('H:i', strtotime($event['Event_R_Time'])); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="ticketPriceAdult">Ticket Price (Adult):</label>
                    <input type="number" id="ticketPriceAdult" name="ticketPriceAdult" value="<?php echo htmlspecialchars($event['Event_R_AdultTicket']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="capacity">Participants Capacity:</label>
                    <input type="number" id="capacity" name="capacity" value="<?php echo htmlspecialchars($event['Event_R_Capacity']); ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="details">Event Details:</label>
                    <textarea id="details" name="details" disabled><?php echo htmlspecialchars($event['Event_R_Description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="picture">Picture Provided:</label>
                    <br>
                    <img src="<?php echo '../Create_event/img/' . htmlspecialchars($event['Event_R_Photo']); ?>" alt="Event Picture" style="max-width: 300px;">
                </div>
                
                <!-- Approve and Reject Buttons Form for each event -->
                <!-- Approve and Reject Buttons Form for each event -->
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?php echo $event['Event_R_Id']; ?>">
                    <button type="submit" name="approve">Approve</button>
                    <button type="button" onclick="document.getElementById('rejectForm<?php echo $event['Event_R_Id']; ?>').style.display = 'block';">Reject</button>
                </form>

                <!-- Rejection Form Modal -->
                <div id="rejectForm<?php echo $event['Event_R_Id']; ?>" style="display: none;">
                    <form method="post" action="">
                        <input type="hidden" name="event_id" value="<?php echo $event['Event_R_Id']; ?>">
                        <textarea name="rejection_reason" placeholder="Enter rejection reason" required></textarea>
                        <button type="submit" name="reject">Submit Rejection</button>
                        <button type="button" onclick="document.getElementById('rejectForm<?php echo $event['Event_R_Id']; ?>').style.display = 'none';">Cancel</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    
    <?php if ($eventApproved): ?>
        <script>
            alert("Event approved and transferred successfully.");
            window.location.href = window.location.href.split("?")[0];
        </script>
    <?php elseif ($eventRejected): ?>
        <script>
            alert("Event rejected successfully.");
            window.location.href = window.location.href.split("?")[0];
        </script>
    <?php endif; ?>

    <!-- Rejection Form Modal -->
    <div id="rejectionModal" style="display: none;">
        <form id="rejectionForm" method="post" action="">
            <input type="hidden" name="event_id" id="rejectionEventId">
            <textarea name="rejection_reason" placeholder="Enter rejection reason" required></textarea>
            <button type="submit" name="reject">Submit Rejection</button>
            <button type="button" onclick="hideRejectionForm()">Cancel</button>
        </form>
    </div>

    <script>
        function showRejectionForm(eventId) {
            document.getElementById('rejectionEventId').value = eventId;
            document.getElementById('rejectionModal').style.display = 'block';
        }

        function hideRejectionForm() {
            document.getElementById('rejectionModal').style.display = 'none';
        }
    </script>
</body>
</html>
