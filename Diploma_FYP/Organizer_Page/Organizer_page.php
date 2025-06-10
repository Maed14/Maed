<?php
session_start();

// Check if organizer_id is set in session
if (!isset($_SESSION['organizer_id'])) {
    // Redirect to login page if organizer_id is not set
    header("Location: login.php");
    exit();
}

// Retrieve organizer_id from session
$organizer_id = $_SESSION['organizer_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Organizer Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="Admin_CSS.css">
</head>
<body>
    <div class="container">
        <h1>Organizer Dashboard</h1>
        
        <a href="../Create_event/CreateEvent.php?organizer_id=<?php echo htmlspecialchars($organizer_id); ?>" class="btn btn-secondary"><i class="fas fa-calendar-check"></i> Create Event</a>
        <a href="../Organizer_event/Organizer_event.php?organizer_id=<?php echo htmlspecialchars($organizer_id); ?>" class="btn btn-tertiary"><i class="fas fa-calendar-times"></i> Your Event</a>
        <a href="../Organizer_info/UserDashboard.php?organizer_id=<?php echo htmlspecialchars($organizer_id); ?>" class="btn btn-tertiary"><i class="fas fa-calendar-times"></i>User Setting</a>
        <a href="../Register&Login/logout.php" class="btn btn-secondary"><i class="fas fa-file-invoice-dollar"></i> Log out</a>
    </div>
</body>
</html>
