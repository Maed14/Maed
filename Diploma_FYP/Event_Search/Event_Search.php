<?php
session_start();
include '../db.php';

$searchResults = [];
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $keyword = '%' . $_POST['keyword'] . '%';
    $state = $_POST['state'];

    if ($conn) {
        $sql = "SELECT * FROM event WHERE Event_Title LIKE ? AND Event_State = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $keyword, $state);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $searchResults[] = $row;
                }
            } else {
                $errorMessage = 'No events found matching your criteria.';
            }

            mysqli_stmt_close($stmt);
        } else {
            $errorMessage = 'Error preparing statement: ' . mysqli_error($conn);
        }
    } else {
        $errorMessage = 'Database connection failed. Please check your connection.';
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Search</title>
    <link rel="stylesheet" href="Event_Search.css">
</head>
<body>

<?php include '../user_header.php'; ?>
    <div class="container">
        <div class="search-section">
            <div class="eventsearch-bar">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="text" name="keyword" placeholder="Search for events" id="search-input">
                    <button type="submit" id="search-button"><i class="search-icon">&#128269;</i></button>
                    <select class="state" id="state-select" name="state">
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Johor">Johor</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Malacca">Malacca</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Putrajaya">Putrajaya</option>
                    </select>
                    <label for="state-select"><i class="location-icon">&#x1F4CD;</i></label>
                </form>
            </div>
        </div>

        <div class="results-section">
            <?php if (!empty($errorMessage)): ?>
                <p><?php echo $errorMessage; ?></p>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($searchResults)): ?>
                <h2>Search Results</h2>
                <div id="events-list">
                    <?php foreach ($searchResults as $event): ?>
                        <a href="../Event_Page/eventpage.php?id=<?php echo htmlspecialchars($event['Event_ID']); ?>" class="event">
                            <div class="event-info">
                                <h3><?php echo htmlspecialchars($event['Event_Title']); ?></h3>
                                <p><?php echo htmlspecialchars($event['Event_Date']); ?></p>
                                <p><?php echo htmlspecialchars($event['Event_Location']); ?></p>
                            </div>
                            <div class="event-image">
                                <img src="../Create_event/img/<?php echo htmlspecialchars($event['Event_Picture']); ?>" alt="<?php echo htmlspecialchars($event['Event_Title']); ?>">
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <h2>Top Matches</h2>
                <!-- Display top matches... -->
            <?php endif; ?>
        </div>
    </div>
    <?php include '../footer.php'; ?>
    
</body>
</html>
