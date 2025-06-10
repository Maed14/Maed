<?php
session_start();
require_once 'db.php';

$status = '';
$message = '';
$users = [];
$messages = [];
$selected_User_Id = isset($_GET['User_Id']) ? $_GET['User_Id'] : 0;
$selected_user_name = '';


if ($selected_User_Id > 0) {
    $user_name_query = "SELECT User_Name FROM contactform WHERE User_Id = $selected_User_Id";
    $user_name_result = $conn->query($user_name_query);

    if ($user_name_result->num_rows > 0) {
        $row = $user_name_result->fetch_assoc();
        $selected_user_name = $row['User_Name'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message']) && isset($_POST['User_Id']) && isset($_POST['user_name'])) {
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $User_Id = filter_input(INPUT_POST, 'User_Id', FILTER_VALIDATE_INT);
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $sender_type = 'admin'; //admin page

    if (!empty($message) && !empty($User_Id) && !empty($user_name)) {
        $stmt = $conn->prepare("INSERT INTO contactform (User_Id, message, sender_type, User_Name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $User_Id, $message, $sender_type, $user_name);

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

    header("Location: Customer_Service.php?User_Id=$User_Id&status=$status&message=" . urlencode($message));
    exit();
}

//user list
$user_query = "SELECT DISTINCT User_Id, User_Name FROM contactform";
$user_result = $conn->query($user_query);

if ($user_result->num_rows > 0) {
    while ($row = $user_result->fetch_assoc()) {
        $users[] = $row;
    }
}

//messages for the selected user
if ($selected_User_Id > 0) {
    $message_query = "SELECT message, sender_type FROM contactform WHERE User_Id = $selected_User_Id ORDER BY Chat_Id ASC";
    $message_result = $conn->query($message_query);

    if ($message_result->num_rows > 0) {
        while ($row = $message_result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="Customer_ServiceCSS.css">
</head>
<body>
    <header class="header">
        <h1>EventFrenzy Admin</h1>
        <hr>
    </header>
    <div class="container">
            <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="../Admin_Page/admin_page.php">Home</a></li>
                <li><a href="../User_Manage/User_Manage.php">User Manage</a></li>
                <li><a href="../Customer_Services/Customer_Service.php">Customer Service</a></li>
                </ul>
            </div>

        <div class="main-content">
            <header class="content-header">
                <h1>Customer Service</h1>
            </header>
            <section class="content">
                <div class="chat-container">
                    <div class="user-list">
                        <h3>Users</h3>
                        <ul id="userList">
                            <?php foreach ($users as $user): ?>
                                <a href="Customer_Service.php?User_Id=<?php echo $user['User_Id']; ?>" <?php echo $selected_User_Id == $user['User_Id'] ? 'class="active"' : ''; ?>>
                                    <li>
                                        <?php echo htmlspecialchars($user['User_Name']); ?>
                                    </li>
                                </a>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="chat-box">
                        <div class="messages" id="messages">
                            <?php foreach ($messages as $msg): ?>
                                <div><?php echo $msg['sender_type'] == 'admin' ? 'Admin: ' : htmlspecialchars($selected_user_name) . ': '; ?><?php echo htmlspecialchars($msg['message']); ?></div>
                            <?php endforeach; ?>
                        </div>
                        <form action="Customer_Service.php" method="POST">
                            <div class="message-input">
                                <input type="hidden" name="User_Id" value="<?php echo $selected_User_Id; ?>">
                                <input type="hidden" name="user_name" value="<?php echo htmlspecialchars($selected_user_name); ?>">
                                <input type="text" name="message" id="messageInput" placeholder="Type your message here">
                                <button type="submit" id="sendButton">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
