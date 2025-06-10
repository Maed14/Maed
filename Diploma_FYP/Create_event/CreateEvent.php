<?php
session_start();
include 'db.php';

$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $email = $_POST['email'] ?? '';
    $eventName = $_POST['eventName'] ?? '';
    $category = $_POST['category'] ?? '';
    $eventDate = $_POST['eventDate'] ?? ''; // Access eventDate directly
    $eventTime = $_POST['eventTime'] ?? '';
    $eventVenue = $_POST['eventVenue'] ?? '';
    $eventState = $_POST['eventState'] ?? '';
    $ticketPriceAdult = $_POST['ticketPriceAdult'] ?? '';
    $ticketPriceChild = $_POST['ticketPriceChild'] ?? 0;
    $capacity = $_POST['capacity'] ?? '';
    $details = $_POST['details'] ?? '';
    $organizer_id = $_SESSION['organizer_id'] ?? ''; // Get the organizer_id from the session

    if (isset($_FILES["picture"])) {
        if ($_FILES["picture"]["error"] === 4) {
            echo "<script>alert('Image does not exist');</script>";
        } else {
            $filename = $_FILES["picture"]["name"];
            $filesize = $_FILES["picture"]["size"];
            $tmpName = $_FILES["picture"]["tmp_name"];

            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $filename);
            $imageExtension = strtolower(end($imageExtension));

            if (!in_array($imageExtension, $validImageExtension)) {
                echo "<script>alert('Invalid image extension');</script>";
            } elseif ($filesize > 5000000) {
                echo "<script>alert('Image size exceeds 5 MB');</script>";
            } else {
                $newImageName = uniqid() . '.' . $imageExtension;
                $uploadDir = 'img/';

                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        die("Failed to create upload directory");
                    }
                }

                $uploadFile = $uploadDir . $newImageName;

                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $sql = "INSERT INTO event_request (Organizer_Id,Organizer_Name, Organizer_Contact, Organizer_Email, Event_R_Title, Event_R_Category, Event_R_Date, Event_R_Time, Event_R_Venue, Event_R_State, Event_R_AdultTicket, Event_R_Capacity, Event_R_Description, Event_R_Photo)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $organizer_id,$name, $contact, $email, $eventName, $category, $eventDate, $eventTime, $eventVenue, $eventState, $ticketPriceAdult, $capacity, $details, $newImageName);
                        if (mysqli_stmt_execute($stmt)) {
                            $successMessage = "Event created successfully.";
                        } else {
                            echo "<script>alert('Error executing statement: " . mysqli_stmt_error($stmt) . "');</script>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<script>alert('Error preparing statement: " . mysqli_error($conn) . "');</script>";
                    }
                } else {
                    echo "<script>alert('Failed to move uploaded file');</script>";
                }
            }
        }
    } else {
        echo "<script>alert('No image uploaded');</script>";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CreateEvent.css">
    <title>Create Event</title>
</head>
<body>
    <?php include 'organizer_header.php'; ?>
    <div class="content">
        <h1 style="font-size: 50px; text-align: center; color: #FF5106;">Create Event</h1>
        <div class="container">
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <form method="post" action="CreateEvent.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Personal Name / Company Name:</label>
                    <input type="text" class="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact">Contact Number:</label>
                    <input type="text" class="text" id="contact" name="contact" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="eventName">Event Name:</label>
                    <input type="text" class="text" id="eventName" name="eventName" required>
                </div>

                <div class="form-group">
                    <label for="category">Event Category:</label>
                    <select id="category" name="category" required>
                        <option value="Music">Music</option>
                        <option value="Nightlife">Night Life</option>
                        <option value="Hobbies">Hobbies</option>
                        <option value="Health">Health</option>
                        <option value="Business">Business</option>
                        <option value="Fooddrinks">Food & Drinks</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="eventDate">Event Date & Time:</label>
                    <input type="date" id="eventDate" name="eventDate" required>
                    <input type="time" id="eventTime" name="eventTime" required>
                </div>

                <div class="form-group">
                    <label for="eventVenue">Event Venue:</label> 
                    <input type="text" class="text" id="eventVenue" name="eventVenue" required>
                </div>

                <div class="form-group">
                    <label for="eventState">State:</label> 
                    <select id="eventState" name="eventState" required>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Putrajaya">Putrajaya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ticketPriceAdult">Ticket Price (Adult):</label>
                    <input type="number" id="ticketPriceAdult" name="ticketPriceAdult" required>
                </div>

                <div class="form-group">
                    <label for="capacity">Participants Capacity:</label>
                    <input type="number" id="capacity" name="capacity" required>
                </div>

                <div class="form-group">
                    <label for="details">Event Details:</label>
                    <textarea id="details" name="details" required></textarea>
                </div>

                <div class="form-group">
                    <label for="picture">Picture Provided:</label>
                    <input type="file" id="picture" name="picture">
                </div>
                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
