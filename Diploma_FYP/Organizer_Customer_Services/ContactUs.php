<?php
session_start();
require_once '../db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$status = '';
$message = '';
$User_Id = $_SESSION['organizer_id'] ?? 0;

$User_Name = '';
if ($User_Id) {
    $stmt = $conn->prepare("SELECT Organizer_Name FROM event_organizer WHERE Organizer_ID = ?");
    $stmt->bind_param("i", $User_Id);
    $stmt->execute();
    $stmt->bind_result($User_Name);
    $stmt->fetch();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $sender_type = 'user';

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO contactform (message, User_Id, User_Name, sender_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $message, $User_Id, $User_Name, $sender_type);

        if ($stmt->execute()) {
            $status = "success";
            $message = "Message sent successfully.";
        } else {
            $status = "error";
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $status = "error";
        $message = "Message field is required.";
    }


    header("Location: ContactUs.php?status=$status&message=" . urlencode($message));
    exit();
}


$messages = [];
$result = $conn->query("SELECT message, sender_type FROM contactform WHERE User_Id='$User_Id' ORDER BY Chat_Id ASC"); // Fetch in ascending order to display latest at bottom
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="contactus.css">
    <title>Contact Us</title>
    
</head>
<body>
    <?php include 'organizer_header.php'; ?>

    <div class="container">

    
        <h1 class="title">Contact Us</h1>
            <span class="text"><p>Get in touch with us now for any inquiries and issues! Sit tight as we get back to you.<p></span>

        <section class="content">
            <div class="chat-container">
                <div class="chat-box">
                    <div class="messages" id="messages">
                        <?php foreach ($messages as $msg): ?>
                            <div><?php echo $msg['sender_type'] == 'user' ? 'You: ' : 'Admin: '; ?><?php echo htmlspecialchars($msg['message']); ?></div>
                        <?php endforeach; ?>
                    </div>
                    <form action="ContactUs.php" method="POST">
                        <div class="message-input">
                            <input type="text" name="message" id="messageInput" placeholder="Type your message here">
                            <button type="submit" id="sendButton">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
