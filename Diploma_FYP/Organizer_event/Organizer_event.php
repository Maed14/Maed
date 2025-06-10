<?php
session_start();

include 'organizer_header.php'; 
require_once '../db.php'; 

// Retrieve organizer_id from session
$organizerId = $_SESSION['organizer_id'];

// Determine filter criteria
$filter = isset($_GET['filter']) ? $_GET['filter'] : ''; // Check if filter is set in URL

// Query to fetch events created by the logged-in organizer based on filter
switch ($filter) {
    case 'approved':
        $sql = "SELECT e.Event_R_Id, e.Event_R_Title, e.Event_R_Venue, e.Event_R_Time, e.Event_R_AdultTicket, e.Event_R_Date, e.Event_R_Status
                FROM event_request e
                WHERE e.Organizer_Id = ? AND e.Event_R_Status = 'approve'";
        break;
    case 'pending':
        $sql = "SELECT e.Event_R_Id, e.Event_R_Title, e.Event_R_Venue, e.Event_R_Time, e.Event_R_AdultTicket, e.Event_R_Date, e.Event_R_Status
                FROM event_request e
                WHERE e.Organizer_Id = ? AND e.Event_R_Status IS NULL";
        break;
    case 'rejected':
        $sql = "SELECT e.Event_R_Id, e.Event_R_Title, e.Event_R_Venue, e.Event_R_Time, e.Event_R_AdultTicket, e.Event_R_Date, e.Event_R_Status
                FROM event_request e
                WHERE e.Organizer_Id = ? AND e.Event_R_Status = 'reject'";
        break;
    default:
        $sql = "SELECT e.Event_R_Id, e.Event_R_Title, e.Event_R_Venue, e.Event_R_Time, e.Event_R_AdultTicket, e.Event_R_Date, e.Event_R_Status
                FROM event_request e
                WHERE e.Organizer_Id = ?";
        break;
}

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $organizerId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Organizer_event.css">
    <title>Your Events</title>
</head>
<body>
    <div class="content">
        <div class="ordersheader">
            <h1 style="font-size: 40px; color: #FF5106;">Your Events</h1>
        </div>
        
        <!-- Filter buttons -->
        <div class="filter-buttons">
            <a href="Organizer_event.php" class="btn btn-filter">All Events</a>
            <a href="Organizer_event.php?filter=approved" class="btn btn-filter">Approved Events</a>
            <a href="Organizer_event.php?filter=pending" class="btn btn-filter">Pending Events</a>
            <a href="Organizer_event.php?filter=rejected" class="btn btn-filter">Rejected Events</a>
        </div>
        
        <div class="order-list">
            <?php
            $currentDate = date('Y-m-d');
            while ($row = $result->fetch_assoc()) {
                $eventDate = date('Y-m-d', strtotime($row['Event_R_Date']));
                if ($eventDate < $currentDate) {
                    $eventStatus = "Ended";
                } elseif ($eventDate == $currentDate) {
                    $eventStatus = "On Going";
                } else {
                    $eventStatus = "Coming Soon";
                }

                // Determine approval status
                $approveStatus = $row['Event_R_Status'];
                if (is_null($approveStatus)) {
                    $approveDisplay = "Pending";
                    $approveClass = "pending";
                } elseif ($approveStatus == 'approve') {
                    $approveDisplay = "Approved";
                    $approveClass = "approved";
                } elseif ($approveStatus == 'reject') {
                    $approveDisplay = "Rejected";
                    $approveClass = "rejected";
                }

                $priceDisplay = ($row['Event_R_AdultTicket'] == 0) ? "Free" : "RM" . htmlspecialchars($row['Event_R_AdultTicket']);
            ?>
            
                <div class="order-item">
                    <div class="order-item-date">
                        <span class="day"><?php echo date('d', strtotime($row['Event_R_Date'])); ?></span>
                        <span class="month"><?php echo date('M', strtotime($row['Event_R_Date'])); ?></span>
                    </div>

                    <div class="order-item-content">
                        <div class="event-header">
                            <strong><?php echo htmlspecialchars($row['Event_R_Title']); ?></strong> 
                            <span class="event_status"><?php echo $eventStatus; ?></span>
                        </div>
                        <span><?php echo htmlspecialchars($row['Event_R_Venue']); ?></span>
                        <span><?php echo date('h:i A', strtotime($row['Event_R_Time'])); ?></span>
                        <span>Price: <?php echo $priceDisplay; ?></span>
                        <div class="status-container <?php echo $approveClass; ?>">
                            <?php echo $approveDisplay; ?>
                        </div>
                        <?php
                        // Determine link destination based on event status
                        if ($row['Event_R_Status'] == 'reject') {
                            $detailsLink = "../Rejected_EventDetail/Rejected_Event_Details.php?eventId=" . $row['Event_R_Id'];
                        } else {
                            $detailsLink = "../Organizer_EventDetail/Event_Details.php?eventId=" . $row['Event_R_Id'];
                        }
                        ?>
                        <a href="<?php echo $detailsLink; ?>" class="btn-details">Details</a>
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
