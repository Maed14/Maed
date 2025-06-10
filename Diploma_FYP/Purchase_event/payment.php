<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null) {
    echo "You must be logged in to proceed.";
    exit();
}

if (isset($_GET['order_id']) && isset($_GET['total_price'])) {
    $order_id = intval($_GET['order_id']);
    $total_price = floatval($_GET['total_price']);
} else {
    echo "Required parameters are missing.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = intval($_POST['order_id']);
    $total_price = floatval($_POST['total_price']);
    $name_on_card = $_POST['name_on_card'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $security_code = $_POST['security_code'];


    $sql = "INSERT INTO order_n (user_id, event_id, total_price) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: ". $conn->error;
        exit();
    }
    $stmt->bind_param("iid", $_SESSION['user_id'], $order_id, $total_price);
    if (!$stmt->execute()) {
        echo "Error executing statement: ". $stmt->error;
        exit();
    } else {
        $order_id = $conn->insert_id;
        header("Location:../Purchase_event/Purchase_Done/Purchase_Done.php?order_id=$order_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="payment.css">
    <script>
        function formatCardNumber(input) {
            const value = input.value.replace(/\D/g, '').substring(0, 16);
            const formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
            input.value = formattedValue;
        }

        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 4) value = value.substring(0, 4);
            if (value.length > 2) {
                input.value = value.substring(0, 2) + '/' + value.substring(2);
            } else {
                input.value = value;
            }
        }
    </script>
</head>

<body>
    <div class="payment-container">

    <div class="imagecon">
        <img src="visalogo.png" alt="Payment Image">
    </div>

        <h2>Payment amount</h2>
        <p class="amount">RM <?php echo htmlspecialchars(number_format($total_price, 2)); ?></p>
        <form action="payment.php?order_id=<?php echo $order_id; ?>&total_price=<?php echo $total_price; ?>" method="post">
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <div class="form-group">
                <label for="name_on_card">Cardholder Name</label>
                <input type="text" id="name_on_card" name="name_on_card" required>
            </div>
            <div class="form-group">
                <label for="card_number">Card number</label>
                <input type="text" id="card_number" name="card_number" oninput="formatCardNumber(this)" placeholder="xxxx xxxx xxxx xxxx" pattern="\d{4} \d{4} \d{4} \d{4}" maxlength="19" required>
            </div>
            <div class="form-group">
                <div class="expiry-date">
                    <label for="expiry_date">Expiry date</label>
                    <input type="text" id="expiry_date" name="expiry_date" oninput="formatExpiryDate(this)" placeholder="MM/YY" pattern="\d{2}/\d{2}" maxlength="5" required>
                </div>
                <div class="security-code">
                    <label for="security_code">CVV</label>
                    <input type="text" id="security_code" name="security_code" placeholder="•••" pattern="\d{3}" maxlength="3" required>
                </div>
            </div>
            <button type="submit" class="pay-button">Pay RM <?php echo htmlspecialchars(number_format($total_price, 2)); ?></button>
        </form>
    </div>
</body>
</html>
