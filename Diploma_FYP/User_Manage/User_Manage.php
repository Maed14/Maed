<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="User_ManageCSS.css">
</head>
<body>
    <header class="header">
        <h1>EventFrenzy User Manage Dashboard</h1>
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
                <h1>User Manage Dashboard</h1>
            </header>
            <section class="content">
                <p>Click a user to view user details or delete user</p>
                
                <div class="user-list">
                    <div id="user-list">
                        <!-- User entries will be dynamically added here -->
                        <?php
                            // Include your database connection file
                            require_once '../db.php';

                            // Pagination configuration
                            $results_per_page = 8; // Number of users per page
                            $sql = "SELECT User_Id,User_Name, User_Email FROM user";
                            $result = $conn->query($sql);
                            $number_of_results = $result->num_rows;

                            // Determine number of pages
                            $number_of_pages = ceil($number_of_results / $results_per_page);

                            // Determine current page number
                            if (!isset($_GET['page'])) {
                                $page = 1;
                            } else {
                                $page = $_GET['page'];
                            }

                            // Calculate SQL LIMIT clause for pagination
                            $this_page_first_result = ($page - 1) * $results_per_page;

                            // SQL query with LIMIT for pagination
                            $sql .= " LIMIT $this_page_first_result, $results_per_page";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                
                                while($row = $result->fetch_assoc()) {
                                    echo "<div class='user-box'>";
                                    echo "<div class='user-box-content'>";
                                    echo "<div>";
                                    echo "<h3>{$row['User_Name']}</h3>";
                                    echo "<p>Email: {$row['User_Email']}</p>"; 
                                    echo "</div>";
                                    echo "<div>";
                                    echo "<button class='delete-button' onclick='confirmDelete(\"{$row['User_Name']}\")'>Delete</button>";
                                    echo "<a href='User_details.php?user_id={$row['User_Id']}' class='user-details-link'><span>View Details</span></a>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "<div class='event-details'>";
                                    echo "</div>";
                                    echo "</div>";
                                 }

                            } else {
                                echo "No users found";
                            }

                            // Pagination links
                            echo "<div class='pagination'>";
                            for ($page = 1; $page <= $number_of_pages; $page++) {
                                echo "<a href='?page=$page'>$page</a> ";
                            }
                            echo "</div>";

                            // Close the database connection
                            $conn->close();
                        ?>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        function confirmDelete(userName) {
            if (confirm(`Are you sure you want to delete user ${userName}?`)) {
                // If user confirms, redirect to delete script or perform AJAX delete
                window.location.href = `delete_user.php?name=${userName}`; // Example of redirecting to delete_user.php with user name as parameter
            } else {
                // If user cancels, do nothing or handle as needed
            }
        }
        </script>


</body>
</html>
