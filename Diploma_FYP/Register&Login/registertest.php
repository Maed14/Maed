<?php
session_start();
// Remove the redirection to login.php if the user session is not set
if (!isset($_SESSION["user"])) {
   // header("Location: login.php");
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="Register.css">
</head>
<body>
<div class="container">
    <?php
    if (isset($_POST["submit"])) {
        $fullName = $_POST["fullname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $passwordRepeat = $_POST["repeat_password"];
        $userSafetyQuestion = $_POST["security-question"];
        $userSafetyAnswer = $_POST["safety_answer"];

        

        $errors = array();

        if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors,"All fields are required");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
        }
        if (strlen($password) < 8) {
            array_push($errors,"Password must be at least 8 characters long");
        }
        if ($password !== $passwordRepeat) {
            array_push($errors,"Password does not match");
        }

        require_once 'db.php';
        $sql = "SELECT * FROM user WHERE User_Email = ?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $rowCount = mysqli_stmt_num_rows($stmt);
            if ($rowCount > 0) {
                array_push($errors,"Email already exists!");
            }
        } else {
            die("Something went wrong");
        }

        if (count($errors) > 0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        } else {
            $sql = "INSERT INTO user (User_Name, User_Email, User_Password, User_SafetyQuestion, User_SafetyAnswer) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt,"sssss", $fullName, $email, $password, $userSafetyQuestion, $userSafetyAnswer);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
        }
    }
    ?>

        <h1>
            <span class="welcome-text">Welcome to </span>
            <span class="event-frenzy">Event Frenzy</span>
        </h1>


    <form action="registertest.php" method="post">
        <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email:">
                </div>

                <div class="form-group position-relative">
                <input type="password" class="form-control" name="password" placeholder="Password:" id="password">
                <i class="fas fa-eye-slash password-toggle" id="togglePassword"></i>
                </div>

                <div class="form-group position-relative">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:" id="repeat_password">
                    <i class="fas fa-eye-slash password-toggle" id="toggleConfirmPassword"></i>
                </div>

            
                <div class="form-group">
                    <select id="User_SafetyQuestion" class="form-control" name="security-question">
                        <option value="mother_surname">What is your mother's surname?</option>
                        <option value="birth_city">In what city were you born?</option>
                        <option value="car_plate">What is your car's plate number?</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="text" class="form-control" name="safety_answer" placeholder="Your Ans:">
                </div>

                <div class="form-btn" style="color: orange;">
                    <input type="submit" class="btn btn-orange" value="Register" name="submit">
                </div>

    </form>

    <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
    </div>
</div>
<script>

    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });


    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const confirmPasswordInput = document.getElementById('repeat_password');
        const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });


</script>

</body>
</html>
