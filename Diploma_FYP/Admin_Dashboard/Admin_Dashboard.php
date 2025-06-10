<?php
include '../db.php';

// Function to execute query and return the result or handle error
function executeQuery($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
    return $result;
}

// Fetch total number of users
$userCountQuery = "SELECT COUNT(*) as total_users FROM user";
$userResult = executeQuery($conn, $userCountQuery);
$userCount = $userResult->fetch_assoc()['total_users'];

// Fetch total number of events
$eventCountQuery = "SELECT COUNT(*) as total_events FROM event";
$eventResult = executeQuery($conn, $eventCountQuery);
$eventCount = $eventResult->fetch_assoc()['total_events'];

// Fetch total number of event organizers
$organizerCountQuery = "SELECT COUNT(DISTINCT Organizer_ID) as total_organizers FROM event_organizer";
$organizerResult = executeQuery($conn, $organizerCountQuery);
$organizerCount = $organizerResult->fetch_assoc()['total_organizers'];

// Fetch upcoming events
$upcomingEventsQuery = "SELECT Event_Title, Event_Date FROM event WHERE Event_Date >= CURDATE() ORDER BY Event_Date ASC";
$upcomingEventsResult = executeQuery($conn, $upcomingEventsQuery);

// Fetch past events
$pastEventsQuery = "SELECT Event_Title, Event_Date FROM event WHERE Event_Date < CURDATE() ORDER BY Event_Date DESC";
$pastEventsResult = executeQuery($conn, $pastEventsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Admin_dashboard_CSS.css">
    <script src="Admin_dashboard_JS.js" defer></script>
</head>
<body>
    <header class="header">
        <h1>EventFrenzy Admin</h1>
    </header>
    <div class="container">
        <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="../Admin_Page/admin_page.php">Home</a></li>
                <li><a href="../User_Manage/User_Manage.php">User Manage</a></li>
                    <li><a href="http://localhost/FINAL_FYP/Customer_Services/Customer_Service.php">Customer Service</a></li>
            </ul>
        </div>
        <div class="main-content">
            <header class="content-header">
                <h1>Analysis</h1>
            </header>
            <section class="content">
                <div class="stats-grid">
                    <div class="stat-card">
                        <h2>Total Events Created</h2>
                        <p><?php echo $eventCount; ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Total Users</h2>
                        <p><?php echo $userCount; ?></p>
                    </div>
                    <div class="stat-card">
                        <h2>Total Event Organizers</h2>
                        <p><?php echo $organizerCount; ?></p>
                    </div>
                </div>
               
                <div class="events-grid">
                    <div class="event-box">
                        <h2>Upcoming Events</h2>
                        <div class="scrollable-content">
                            <ul>
                                <?php
                                if ($upcomingEventsResult->num_rows > 0) {
                                    while($row = $upcomingEventsResult->fetch_assoc()) {
                                        echo '<li>' . htmlspecialchars($row["Event_Title"]) . ' - ' . htmlspecialchars($row["Event_Date"]) . '</li>';
                                    }
                                } else {
                                    echo '<li>No upcoming events</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="event-box">
                        <h2>Past Events</h2>
                        <div class="scrollable-content">
                            <ul>
                                <?php
                                if ($pastEventsResult->num_rows > 0) {
                                    while($row = $pastEventsResult->fetch_assoc()) {
                                        echo '<li>' . htmlspecialchars($row["Event_Title"]) . ' - ' . htmlspecialchars($row["Event_Date"]) . '</li>';
                                    }
                                } else {
                                    echo '<li>No past events</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
