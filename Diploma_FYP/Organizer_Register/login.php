<?php
session_start();

$error_message = ''; // Initialize error message variable

if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "db.php"; // Ensure db.php contains your database connection details

    $sql = "SELECT * FROM event_organizer WHERE Organizer_Email = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            // Verify password using password_verify() for secure password storage
            if ($password == $user["Organizer_Password"]) {
                $_SESSION["organizer_id"] = $user["Organizer_ID"];
                $_SESSION["organizer_name"] = $user["Organizer_Name"];
                header("Location: http://localhost/FINAL_FYP/Organizer_Page/Organizer_page.php");
                exit();
            } else {
                $error_message = "Password does not match";
            }
        } else {
            $error_message = "Email does not match";
        }
    } else {
        $error_message = "Error occurred while preparing SQL statement";
    }
}

if (!empty($error_message)) {
    echo "<div class='alert alert-danger'>$error_message</div>";
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
            <span class="welcome-text">Log In As</span>
            <span class="event-frenzy">Organizer</span>
        </h1>

        <?php if (!empty($error_message)) { echo "<div class='alert alert-danger'>$error_message</div>"; }?>

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

        <div><p>Not registered yet? <a href="register.php">Register Here</a></p></div>
        <div><p>Forgot Password? <a href="forgotPassword.php">Click Here</a></p></div>
    </div>
</body>
</html>
