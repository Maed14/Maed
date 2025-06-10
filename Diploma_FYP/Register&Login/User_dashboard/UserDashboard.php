<?php
session_start();

$userId = $_SESSION['user_id']; 
require_once 'db.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getUserData($conn, $userId) {
    $sql = "SELECT * FROM user WHERE User_Id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
        } else {
            echo "User not found.";
            exit;
        }

        mysqli_stmt_close($stmt);
        return $user;
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        exit;
    }
}

function updateEmail($conn, $userId, $newEmail) {
    $sql = "UPDATE user SET User_Email = ? WHERE User_Id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $newEmail, $userId);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            echo "Error updating email: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        return false;
    }
}

function updatePassword($conn, $userId, $newPassword) {
    $sql = "UPDATE user SET User_Password = ? WHERE User_Id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $newPassword, $userId);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            echo "Error updating password: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        return false;
    }
}

function updateUserDetails($conn, $userId, $newName, $newContact) {
    $sql = "UPDATE user SET User_Name = ?, User_Contact = ? WHERE User_Id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $newName, $newContact, $userId);
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            echo "Error updating user details: " . mysqli_error($conn);
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['new-email'])) {
        $newEmail = $_POST['new-email'];
        if (updateEmail($conn, $userId, $newEmail)) {
            mysqli_close($conn);
            header("Location: UserDashboard.php?success=email");
            exit;
        }
    } elseif (isset($_POST['new-password']) && isset($_POST['current-password'])) {
        $newPassword = $_POST['new-password'];
        if (updatePassword($conn, $userId, $newPassword)) {
            mysqli_close($conn);
            header("Location: UserDashboard.php?success=password");
            exit;
        }
    } elseif (isset($_POST['new-name']) && isset($_POST['new-contact'])) {
        $newName = $_POST['new-name'];
        $newContact = $_POST['new-contact'];
        if (updateUserDetails($conn, $userId, $newName, $newContact)) {
            mysqli_close($conn);
            header("Location: UserDashboard.php?success=details");
            exit;
        }
    }
}

$user = getUserData($conn, $userId);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<link rel="stylesheet" href="UserDashboard.css">

</head>
<body>
<div class="sidebar">
    <a href="#" data-target="contact-info">Contact Info</a>
    <a href="#" data-target="change-email">Change Email</a>
    <a href="#" data-target="change-password">Change Password</a>
    
</div>

<div class="container">
    <header class="header">
        <div class="logo"><b>User Dashboard</b></div>
        <div class="user-menu">
            <div class="dropdown">
                <a href="#">Account</a>
                <a href="#">Logout</a>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="form-section active" id="contact-info">
            <h2>Account Information</h2>
            <hr>
            <form id="contact-info-form" method="post" action="">
                <div class="input-group">
                    <div>
                        <label for="new-name">Full Name:</label>
                        <input type="text" id="new-name" name="new-name" class="half-width" value="<?php echo htmlspecialchars($user['User_Name']); ?>" disabled>
                    </div>
                </div>
                <div>
                    <label for="new-contact">Cell Phone Number:</label>
                    <input type="text" id="new-contact" name="new-contact" value="<?php echo htmlspecialchars($user['User_Contact']); ?>" disabled>
                </div>
                <button type="button" id="edit-button">Edit</button>
                <button type="submit" id="save-button" style="display: none;">Save</button>
            </form>
        </div>

        <div class="form-section" id="change-email">
            <h2>Change Email</h2>
            <form id="email-form" method="post" action="">
                <label for="new-email">New Email:</label>
                <input type="email" id="new-email" name="new-email" required><br><br>
                <button type="submit">Update Email</button>
            </form>
        </div>

        <div class="form-section" id="change-password">
            <h2>Change Password</h2>
            <form id="password-form" method="post" action="">
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current-password" required>
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required><br><br>
                <button type="submit">Update Password</button>
            </form>
        </div>
        
        <div class="form-section" id="credit-card">
            <h2>Credit/Debit Card</h2>
            <form id="card-form">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" required>
                <label for="card-expiry">Expiry Date:</label>
                <input type="text" id="card-expiry" required>
                <label for="card-cvc">CVC:</label>
                <input type="text" id="card-cvc" required><br><br>
                <button type="submit">Update Card</button>
            </form>
        </div>
    </main>
</div>

<script>
// Function to switch active section
function switchSection(target) {
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(target).classList.add('active');
}

// Add event listeners to sidebar links
document.querySelectorAll('.sidebar a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('data-target');
        switchSection(target);
    });
});

// Toggle between edit and save buttons
document.getElementById('edit-button').addEventListener('click', function() {
    document.getElementById('edit-button').style.display = 'none';
    document.getElementById('save-button').style.display = 'inline-block';

    // Enable input fields for editing
    document.querySelectorAll('#contact-info-form input').forEach(input => {
        input.disabled = false;
    });
});

const urlParams = new URLSearchParams(window.location.search);
const successMessage = urlParams.get('success');
if (successMessage === 'email') {
    alert('Email updated successfully.');
} else if (successMessage === 'password') {
    alert('Password updated successfully.');
} else if (successMessage === 'details') {
    alert('User details updated successfully.');
}
</script>
</body>
</html>
