<?php
include '../db.php';

if (isset($_POST['event_id']) && isset($_POST['user_id'])) {
    $event_id = intval($_POST['event_id']);
    $user_id = intval($_POST['user_id']);


    error_log("Event ID: $event_id, User ID: $user_id");

    
    $checkQuery = "SELECT * FROM save_event WHERE event_id = ? AND user_id = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ii", $event_id, $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Event already liked.";
    } else {
        
        $sql = "INSERT INTO save_event (event_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ii", $event_id, $user_id);
        if ($stmt->execute()) {
            echo "Event liked successfully.";
        } else {
            die("Error: " . $stmt->error);
        }
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request. Event ID or User ID missing.");
}
?>
