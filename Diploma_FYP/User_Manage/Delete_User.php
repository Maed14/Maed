<?php
// Include your database connection file
require_once '../db.php';

// Retrieve user name from URL parameter
$userName = $_GET['name'];

// SQL query to delete user
$sql = "DELETE FROM user WHERE User_Name = '$userName'";
if ($conn->query($sql) === TRUE) {
    // Close the database connection
    $conn->close();

    // Redirect back to previous page
    echo "<script>window.history.back();</script>";
    exit;
} else {
    echo "Error deleting user: " . $conn->error;
}

// Close the database connection
$conn->close();
?>
