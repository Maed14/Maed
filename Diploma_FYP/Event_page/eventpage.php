<?php
session_start(); // Start the session
include 'db.php';

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $sql = "SELECT * FROM event WHERE Event_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
} else {
    echo "No event ID provided.";
    exit();
}

// Assuming user_id is stored in the session
$user_id = $_SESSION['user_id'];

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get the current date
$current_date = date('Y-m-d');
$event_date = $event['Event_Date'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="event_page.css">
    <title>Event Page</title>
</head>
<body>
    <?php include '../user_header.php'; ?>

    <div class="content-container">
        <div class="container">
            <div class="image-container">
                <img src="<?php echo '../Create_event/img/' . htmlspecialchars($event['Event_Picture']); ?>" alt="Event Picture" style="max-width: 600px;">
            </div>
        </div>

        <div class="date">
            <p style="font-weight: bold;">Date Time : <?php echo htmlspecialchars($event['Event_Date']) . ' ' . htmlspecialchars($event['Event_Time']); ?></p> 
            <button class="like-button" title="Like" data-event-id="<?php echo htmlspecialchars($event['Event_ID']); ?>" data-user-id="<?php echo htmlspecialchars($user_id); ?>">
                <span class="like-icon">&#9825;</span>
            </button>
        </div>

        <div class="title-container">
            <h1 style="font-size: 50px; margin: 20px 0px;"><?php echo htmlspecialchars($event['Event_Title']); ?></h1>
            <div class="price-container">
                Buy now

                <?php if ($event_date < $current_date): ?>
                    <button style="display: inline;" disabled>Ended</button>
                <?php else: ?>
                    <a href="../Purchase_event/PurchaseEvent.php?id=<?php echo htmlspecialchars($event['Event_ID']); ?>" style="display: inline;">
                        <div class="price">
                            Price: RM<?php echo htmlspecialchars($event['Adult_Ticket']); ?>
                        </div>
                    </a>
                <?php endif; ?>
                
            </div>
                
            
        </div>

        <div class="organizer">
            <div class="logo">
                <img src="img/organizer.jpg" alt="event4">
            </div>
            <div class="name">
                By <span style="font-weight: bold;"> <?php echo htmlspecialchars($event['Organizer_Name']); ?></span>
            </div>
        </div>

        <span style="font-size: 26px; font-weight: bold">Location</span>
        <div><?php echo htmlspecialchars($event['Event_Location']).','. htmlspecialchars($event['Event_State']); ?></div>
        <br>
        <span style="font-size: 26px; font-weight: bold">About this event</span>
        <div>
            <?php echo nl2br(htmlspecialchars($event['Event_Description'])); ?>
        </div>
    </div>

    <?php include '../HomePage/footer.php'; ?>

    <script>
        document.querySelector('.like-button').addEventListener('click', function() {
            var eventId = this.getAttribute('data-event-id');
            var userId = this.getAttribute('data-user-id');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'save_like.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText); 
                    console.log(xhr.responseText);
                } else {
                    alert('An error occurred. Please try again.');
                    console.log('Error: ' + xhr.status);
                }
            };
            xhr.onerror = function() {
                alert('Request failed.');
                console.log('Request failed.');
            };
            xhr.send('event_id=' + eventId + '&user_id=' + userId);
        });
    </script>
</body>
</html>
