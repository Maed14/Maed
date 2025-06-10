<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../db.php'; 


$user_id = $_SESSION['organizer_id'] ?? null; // Assuming user_id is stored in session

$organizer_name = 'Guest'; // Default name if not found
if ($user_id) {
    $query = "SELECT Organizer_Name FROM event_organizer WHERE Organizer_Id = ?";
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fetched_name);
        if (mysqli_stmt_fetch($stmt)) {
            $organizer_name = htmlspecialchars($fetched_name);
        }
        mysqli_stmt_close($stmt);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body, html {
            margin: 0;
            padding: o;
            font-family: sans-serif;
        }
/*vvvvvvvvvvvvvvvvvvvvvvvvv    Header CSS    VVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVVV */
        header {
            box-shadow: 0px 4px 4px -2px rgba(0, 0, 0, 0.2);
            background-color: white;
        }
        
        .pad{
            padding: 5px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;

        }
        /* Style for the search bar */
        .search-bar {
            flex-grow: 1;
            margin-right: 20px;
            display: flex;
        }

        .search-bar input{
            background-color: #EAEAEA;
            min-width: 10%;
            padding: 10px;
            height: 20px;
            border: 1px solid rgba(0, 0, 0, 0.4);
        }
        
        .header-links {
            display: flex;
            align-items: center;
        }
        
        .header-links a {
            color: black;
            padding: 13px;
            text-decoration: none;
            margin-right: 40px;
       
            border-radius: 15px;
            
        }

        .header-links a:hover {
            border-color: #FF5106;
            box-shadow: 0px 0px 2px 1px rgba(255, 81, 6, 0.2);
            color: #FF5106;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            
            width: 200px;
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 15px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
/*^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^    Header CSS   ^^^^^^^^^^^^^^^^^^^^^^^^^^^^ */


    </style>
    </head>
    </body>
    <header id="header">
        <div class="pad">
        <a href="../Organizer_Page/Organizer_page.php" style="text-decoration: none; color: #FF5106;"><h1 style="padding-right: 30px; font-family: fantasy; letter-spacing: 1px;">EventFrenzy</h1></a>
        
        
        <div class="header-links">
            <div class="dropdown">
                <a href="#">Help Centre</a>
                <div class="dropdown-content">
                    <a href="../HelpCentre/HelpCentre.php">Help Centre</a>
                    <a href="../Organizer_Customer_Services/ContactUs.php">Contact us</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="#"><?php echo $organizer_name; ?></a>
                <div class="dropdown-content">
                    <a href="../Organizer_event/Organizer_event.php">Your Event</a>
                    <a href="../UserDashboard.php">User Setting</a>
                    <a href="../Register&Login/logout.php">Log Out</a>

                </div>
            </div>
            
        </div>
        </div>
    </header>
    
    <script>
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            var header = document.getElementById("header");
            if (document.body.scrollTop > 80 || document.documentElement.scrollTop > 80) {
                header.style.position = "fixed";
                header.style.top = "0";
                header.style.width = "100%";
            } else {
                header.style.position = "static";
            }
        }
    </script>

    </body>
    </html>