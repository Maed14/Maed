<?php
session_start(); // Start session at the beginning

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $userSafetyQuestion = $_POST["security-question"];
    $userSafetyAnswer = $_POST["safety_answer"];

    include "../db.php";
    $sql = "SELECT * FROM user WHERE User_Email =?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        if ($user) {
            // Compare safety question and answer with database
            if ($userSafetyQuestion === $user["User_SafetyQuestion"] && $userSafetyAnswer === $user["User_SafetyAnswer"]) {
                $_SESSION["user"] = "yes"; // Set session variable
                $_SESSION["user_id"] = $user["User_ID"]; // Store user_id for future use
                header("Location: ../Change_Password/Change_Password.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Question or Answer does not match</div>";
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
            <span class="welcome-text">Forgot </span>
            <span class="event-frenzy">Password</span>
        </h1>

        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <select id="User_SafetyQuestion" class="form-control" name="security-question" required>
                    <option value="" selected>Choose your security question</option>
                    <option value="mother_surname">What is your mother's surname?</option>
                    <option value="birth_city">In what city were you born?</option>
                    <option value="car_plate">What is your car's plate number?</option>
                </select>
            </div>

            <div class="form-group">
                <input type="text" class="form-control" name="safety_answer" placeholder="Your Ans:" required>
            </div>

            <div class="form-btn" style="color: orange;">
                <input type="submit" class="btn btn-orange" value="Log in" name="login">
            </div>
        </form>

        <div><p>Not registered yet <a href="registertest.php">Register Here</a></p></div>
    </div>
</body>
</html>