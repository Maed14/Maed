<?php

include 'db.php';


$current_date = date('Y-m-d');

$sql = "SELECT * FROM event WHERE Event_Category = 'Music' AND Event_Date >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_date);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="category.css">
    <title>Health Events</title>
</head>

<body>
    <?php include '../user_header.php'; ?>
    <h2>Welcome to the Music World</h2>
    
    <div class="events-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<a href="../Event_page/eventpage.php?id=' . htmlspecialchars($row["Event_ID"]) . '">';
                echo '<div class="event-card">';
                echo '<img src="../Create_event/img/' . htmlspecialchars($row["Event_Picture"]) . '" alt="Event Picture" style="max-width: 300px;">';
                echo '<div class="event-card-content">';
                echo '<div class="event-title">' . htmlspecialchars($row["Event_Title"]) . '</div>';
                echo '<div class="event-details">';
                echo '<div class="event-date">' . htmlspecialchars($row["Event_Date"]) . ' &nbsp; '. htmlspecialchars($row["Event_Time"]) . '</div>';
                echo '<div class="event-location">' . htmlspecialchars($row["Event_Location"]) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</a>'; // Close the anchor tag
            }
        } else {
            echo "No events found.";
        }
        $conn->close();
        ?>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
