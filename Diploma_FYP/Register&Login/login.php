<?php
session_start(); // Start the session at the beginning

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "db.php"; // Include the database connection

    // Prepare SQL statement to fetch user details based on email
    $sql = "SELECT * FROM user WHERE User_Email = ?";
    $stmt = mysqli_stmt_init($conn);
    
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Verify the password
            if ($password === $user["User_Password"]) { // Assuming passwords are stored in plain text (not recommended)
                // Store user information in session
                $_SESSION["user_id"] = $user["User_ID"];
                $_SESSION["user_name"] = $user["User_Name"];
                // Redirect to the homepage
                header("Location: ../HomePage/HomePage.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Password does not match</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Email does not match</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Error occurred while preparing SQL statement</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="Register.css">
</head>
<body>
    <div class="container">
        <h1>
            <span class="welcome-text">Log</span>
            <span class="event-frenzy">In</span>
        </h1>

        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control" required>
            </div>

            <div class="form-btn" style="color: orange;">
                <input type="submit" class="btn btn-orange" value="Log in" name="submit">
            </div>
        </form>

        <div><p>Not registered yet? <a href="registertest.php">Register Here</a></p></div>
        <div><p>Forgot Password? <a href="forgotPassword.php">Click Here</a></p></div>
        <div><p>Log In as a Organizer? <a href="../Organizer_Register/login.php">Click Here</a></p></div>
    </div>
</body>
</html>
