<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="InvoiceCSS.css">
</head>
<body>
    <header class="header">
        <h1>EventFrenzy Invoice</h1>
    </header>
    <div class="container">
            <div class="sidebar">
            <h2>Dashboard</h2>
            <ul>
                <li><a href="../Admin_Page/admin_page.php">Home</a></li>
                <li><a href="../User_Manage/User_Manage.php">User Manage</a></li>
                <li><a href="../Invoice/Invoice.php">Invoice</a></li>
                <li><a href="../Customer_Services/Customer_Service.php">Customer Service</a></li>
            </ul>
        </div>

        <div class="main-content">
            <header class="content-header">
                <h1>Invoice Dashboard</h1>
            </header>
            <section class="content">
                <div class="accordion">
                    <div class="accordion-item">
                        <button class="accordion-button">Spark Dancing Event</button>
                        <div class="accordion-content">
                            <!-- Invoice for Event Name 1 -->
                            <div class="invoice-box">
                                <div class="invoice-header">
                                    <h1>Invoice</h1>
                                    <div class="invoice-details">
                                        <p>Invoice No 12345</p>
                                        <p>May 11, 2024</p>
                                    </div>
                                </div>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Price per ticket</th>
                                            <th>Quantity</th>
                                            <th>Amount Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Attendees</td>
                                            <td>RM 50.00</td>
                                            <td>100</td>
                                            <td>RM 5,000.00</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="total">
                                    <p>Total Amount <span>RM 5,000.00</span></p>
                                </div>
                                <div class="payment-info">
                                    <p><strong>Event Organizer</strong></p>
                                    <p>Spark</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button">Event Name 2</button>
                        <div class="accordion-content">
                            <!-- Invoice for Event Name 2 -->
                            <!-- Similar to the above invoice structure -->
                        </div>
                    </div>
                    <!-- Add more accordion items for other events and their invoices as needed -->
                </div>
            </section>
        </div>
    </div>
    <script src="InvoiceJS.js"></script>
</body>
</html>
