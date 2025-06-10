<?php
include 'db.php';
session_start();

if (isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $sql = "SELECT * FROM event WHERE Event_ID =?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: ". $conn->error;
        exit();
    }
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();
} else {
    echo "No event ID provided.";
    exit();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null) {
    echo "You must be logged in to make a purchase.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $total_price = floatval($_POST['total_price']);

    $sql = "INSERT INTO `order_n` (user_id, event_id, total_price) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error preparing statement: ". $conn->error;
        exit();
    }
    $stmt->bind_param("iid", $user_id, $event_id, $total_price);
    if (!$stmt->execute()) {
        echo "Error executing statement: ". $stmt->error;
        exit();
    } else {
        $order_id = $conn->insert_id;
        header("Location:payment.php?order_id=$order_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo htmlspecialchars($event['Event_Title']); ?></title>
    <link rel="stylesheet" href="PurchaseEvent.css">
    <?php include 'header.php'?>
</head>
<body>
    <div class="container">
        <div class="event-details">
            <h3><?php echo htmlspecialchars($event['Event_Title']); ?></h3>
            <div class="info">
                <p><?php echo htmlspecialchars($event['Event_Date']) . ' ' . htmlspecialchars($event['Event_Time']); ?></p>
                <hr><br>
            </div>
            <div class="admission">
                <div class="content">
                    <div class="description">
                        <p>General Admission</p>
                    </div>
                    <div class="controls">
                        <button class="minus" onclick="decrease()">-</button>
                        <span id="quantity">0</span>
                        <button class="plus" onclick="increase()">+</button>
                    </div>
                </div>

                <hr>

                <div class="price">
                    <p>RM <?php echo htmlspecialchars($event['Adult_Ticket']); ?></p>
                </div>
            </div>

            <div class="order-summary"><br>
                <h4>Order Summary</h4>
                <p id="order-summary-details">0 x General Admission RM0.00</p>
                <h4 id="order-summary-total">Total RM0.00</h4>
                <hr>
            </div>

            <div class="register">
                <form method="get" id="checkout-form" action="payment.php">
                    <input type="hidden" name="total_price" id="total_price" value="0">
                    <input type="hidden" name="order_id" id="order_id" value="<?php echo $event_id; ?>">
                    <button type="button" class="button" onclick="checkOut()">Check Out</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        let quantity = 0;
        const pricePerTicket = <?php echo json_encode($event['Adult_Ticket']); ?>;

        function updateOrderSummary() {
            const orderDetails = `${quantity} x General Admission RM${(quantity * pricePerTicket).toFixed(2)}`;
            const orderTotal = `Total RM${(quantity * pricePerTicket).toFixed(2)}`;
            document.getElementById('order-summary-details').textContent = orderDetails;
            document.getElementById('order-summary-total').textContent = orderTotal;
            document.getElementById('total_price').value = (quantity * pricePerTicket).toFixed(2);
        }

        function increase() {
            quantity++;
            document.getElementById('quantity').textContent = quantity;
            updateOrderSummary();
        }

        function decrease() {
            if (quantity > 0) {
                quantity--;
                document.getElementById('quantity').textContent = quantity;
                updateOrderSummary();
            }
        }

        function checkOut() {
            if (quantity === 0) {
                alert("Please select at least one ticket before checking out.");
            } else {
                const total_price = document.getElementById('total_price').value;
                const event_id = <?php echo json_encode($event_id); ?>;
                window.location.href = `payment.php?order_id=${event_id}&total_price=${total_price}`;
            }
        }
    </script>

<?php include 'footer.php'?>
</body>
</html>
