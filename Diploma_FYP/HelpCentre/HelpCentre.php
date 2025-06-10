<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Centre</title>
    <style>
        .card-container {
            margin: 8vw;
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .card {
            font-size: 20px;
            border: 2px solid #ccc;
            border-radius: 15px;
            padding: 10px;
            margin: 20px;
            width: 150px;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            width: 300px;
            height: 100px;
            background-color: #f9f9f9;
        }

        .card:hover {
            border-color: #FF5106;
            box-shadow: 0px 0px 2px 1px rgba(255, 81, 6, 0.2);
            color: #FF5106;
        }

        .contact {
            background-color: #FF5106;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
            
        }

        .button {
            display: flex;
            justify-content: center;
            align-items: center;
            padding-bottom: 50px;
        }

        .contact:hover {
            background-color: #FF6A39;
        }

        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <?php include '../user_header.php'; ?>
    <h1 style="font-size: 70px; text-align: center;">How can we help?</h1>
    <hr>
    
    <h1 style="margin: 40px 8vw 0 8vw;">Attending featured articles</h1>
    <div class="card-container">
        <a href="FindYourTickets.php">
            <div class="card">
                <p>Find Your tickets</p>
            </div>
        </a>
        <a href="RequestRefund.php">
            <div class="card">
                <p>Request a refund</p>
            </div>
        </a>
        <a href="ContactOrganizer.php">
            <div class="card">
                <p>Contact the event organizer</p>
            </div>
        </a>
        <a href="Transfer.php">
            <div class="card">
                <p>Transfer tickets to someone else</p>
            </div>
        </a>
        <a href="Edit.php">
            <div class="card">
                <p>Edit your order information</p>
            </div>
        </a>
    </div>

    <h1 style="margin: 40px 8vw 0 8vw;">Organizing featured articles</h1>
    <div class="card-container">
    <a href="CreateEvent.php">
        <div class="card">
        <p>Create an event</p>
        </div>
    </a>
    <a href="AddImage.php">
        <div class="card">
        <p>Add an image to your event</p>
        </div>
    </a>
    <a href="EventStatus.php">
        <div class="card">
        <p>Change your event status</p>
        </div>
    </a>
    <a href="ChangeDateTime.php">
        <div class="card">
        <p>Change the event date and time</p>
        </div>
    </a>
    <a href="CancelEvent.php">
        <div class="card">
        <p>Cancel your event</p>
        </div>
    </a>
    </div>

    <h1 style="font-size: 40px; text-align: center;">Still have question?</h1>
    
    <div class="button">
    <a href="ContactUs.php">
        <button class="contact">Contact Us</button>
    </a>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>