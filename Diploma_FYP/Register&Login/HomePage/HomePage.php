<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once("../db.php");

$user_id = $_SESSION["user_id"];
$sql = "SELECT User_Name FROM user WHERE User_ID = ?";
$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $user_name = $user ? $user["User_Name"] : "Guest";
} else {
    $user_name = "Guest";
}

$_SESSION["user_name"] = $user_name; // Update the session with the user's name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home.css">
    
 
</head>

<body>
<?php include '../header.php'; ?>

    <div class="image-container">
        <img src="img/pic1.jpeg">
    </div>

    <div class="category-wrapper">
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/music.png" alt="Music Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Music</p>
            </div>
        </div></a>
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/Night.png" alt="Night Life Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Nightlife</p>
            </div>
        </div></a>
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/hob.png" alt="Hobbies Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Hobbies</p>
            </div>
        </div></a>
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/hea.png" alt="Health Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Health</p>
            </div>
        </div></a>
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/food.png" alt="Food & Drinks Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Food & Drinks</p>
            </div>
        </div></a>
        <a href="#"><div class="card">
            <div class="circle-container">
                <div class="backimg">
                    <img src="img/bus.png" alt="Business Picture">
                </div>
            </div>
            <div class="text-under-container">
                <p>Business</p>
            </div>
        </div></a>
    </div>
    

<hr>

<h1 style="margin: 40px 40px 40px 8vw; font-family: sans-serif;">Events in Kuala Lumpur</h1>

<div class="card-container">
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event1.jpeg" alt="event1"></div>
        <h3>Introduction to Artificial Intelligence (AI)</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event2.jpeg" alt="event2"></div>
        <h3>Free Confidence and Communication Mastery Workshop</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event3.jpeg" alt="event3"></div>
        <h3>Train-The-Trainer 1 Day Training in Petaling Jaya</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event4.jpeg" alt="event4"></div>
        <h3>Sip & Paint Night: Starry Night by Van Gogh</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event5.jpeg" alt="event5"></div>
        <h3>Yoga & Brunch</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    <div class="card1"><a href="#">
        <div class="eventpic"><img src="img/event6.jpeg" alt="event6"></div>
        <h3>Great British Circus Puchong</h3>
        <div class="des">
        <p>Mon, May 8 · 7 : 00PM</p>
        <p>Bukit Bintang</p>
        <br>
        <p>From RM 0.00 <br> My Tech Academy</p>
        </div>
    </a></div>
    
</div>

<?php include '../footer.php'; ?>

</body>
</html>
