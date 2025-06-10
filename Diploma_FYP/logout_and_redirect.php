<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: http://localhost/FINAL_FYP/Organizer_Register/register.php");
exit();
?>
