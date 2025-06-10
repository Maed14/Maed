<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["change_password"])) {
    if (!empty($_POST)) {
        $newPassword = $_POST["new_password"];
        $confirmPassword = $_POST["confirm_password"];

        if ($newPassword !== $confirmPassword) {
            echo "<div class='alert alert-danger'>Passwords do not match</div>";
        } else {
            $userId = $_SESSION["user_id"]; 

            $sql = "UPDATE user SET User_Password =? WHERE User_ID =?";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "si", $newPassword, $userId);
                if (mysqli_stmt_execute($stmt)) {
                    echo "<div class='alert alert-success'>Password changed successfully</div>";
                    header("refresh:1.5; url=http://localhost/FINAL_FYP/Register&Login/login.php"); // Redirect to login.php after 3 seconds
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error occurred while updating password</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error occurred while preparing SQL statement</div>";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="Change_Password.css">
</head>
<body>
    <div class="container">
        <h1>
            <span class="welcome-text">Change </span>
            <span class="event-frenzy">Password</span>
        </h1>

        <form action="change_password.php" method="post">
        <div class="form-group position-relative">
            <input type="password" placeholder="Enter New Password" name="new_password" class="form-control" required id="new_password">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-eye-slash password-toggle" id="toggleNewPassword" onclick="togglePassword('new_password', this)"></i>
                </span>
            </div>
        </div>

        <div class="form-group position-relative">
        <input type="password" placeholder="Confirm New Password" name="confirm_password" class="form-control" required id="confirm_password">
            <div class="input-group-append">
                <span class="input-group-text">
                    <i class="fas fa-eye-slash password-toggle" id="toggleNewPassword" onclick="togglePassword('new_password', this)"></i>
                </span>
            </div>
        </div>

            <div class="form-btn">
                <input type="submit" class="btn btn-orange" value="Change Password" name="change_password">
            </div>
        </form>
    </div>

    <script>
        function togglePassword(id, icon) {
            const passwordField = document.getElementById(id);
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>
</html>
