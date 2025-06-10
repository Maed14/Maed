<?php

$servername = "localhost";
$username = "yf";
$password = "abc123";
$dbname = "final_fyp";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "INSERT INTO users (User_Name, User_Contact, User_Email, User_Password, User_SecurityQuestion_1, security_question_2, security_question_3) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);


$stmt->bind_param("ssssss", $username, $contact, $email, $hashedPassword, $securityQuestion1, $securityQuestion2, $securityQuestion3);


$username = $_POST['username'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
$securityQuestion1 = $_POST['security-question1'];
$securityQuestion2 = $_POST['security-question2'];
$securityQuestion3 = $_POST['security-question3'];


if ($stmt->execute()) {
    echo "User registered successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$stmt->close();
$conn->close();
?>
