<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
    padding: 0;
    font-family: sans-serif;
}

header {
    box-shadow: 0px 4px 4px -2px rgba(0, 0, 0, 0.2);
    background-color: white;
    z-index: 2;
}

.pad {
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

.search-bar input {
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

    </style>
</head>
<body>
    <header id="header">
        <div class="pad">
            <a href="../HomePage/HomePage.php" style="text-decoration: none; color: #FF5106;">
                <h1 style="padding-right: 30px; font-family: fantasy; letter-spacing: 1px;">EventFrenzy</h1>
            </a>
            <div class="search-bar">
                <input type="text" placeholder="Search..." style="border-radius: 50px 0px 0px 50px;" id="search-input">
                <input type="text" placeholder="Search by location..." style="border-radius: 0px 50px 50px 0px;" id="location-input">
            </div>
            <div class="header-links">
                <div class="dropdown">
                    <a href="#">Help Centre</a>
                    <div class="dropdown-content">
                        <a href="../HelpCentre/HelpCentre.php">Help Centre</a>
                        <a href="../HelpCentre/ContactUs.php">Customer Services</a>
                    </div>
                </div>
                <div class="dropdown">
                    <a href="#"><?php echo htmlspecialchars($_SESSION["user_name"] ?? 'Guest'); ?></a>
                    <div class="dropdown-content">
                        <a href="../YourOrder/YourOrder.php">Your Order</a>
                        <a href="../Saved_Event/saved_event.php">Your Favourite</a>
                        <a href="../Register&login/User_dashboard/UserDashboard.php">User Setting</a>
                        <a href="http://localhost/FINAL_FYP/logout_and_redirect.php">Create Event</a>
                        <a href="../Register&Login/logout.php">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    
    <script>
        const searchInput = document.getElementById('search-input');
        const locationInput = document.getElementById('location-input');
        const searchBtn = document.getElementById('search-btn');

        searchInput.addEventListener('click', redirectToSearch);
        locationInput.addEventListener('click', redirectToSearch);
        searchBtn.addEventListener('click', redirectToSearch);

        function redirectToSearch() {
            // Animate the page transition
            document.body.style.transition = 'all 0.5s ease-in-out';
            document.body.style.opacity = 0;

            setTimeout(function() {
                window.location.href = 'http://localhost/FINAL_FYP/Event_Search/Event_Search.php';
            }, 500);
        }

        window.onscroll = function() {
            scrollFunction();
        };

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
