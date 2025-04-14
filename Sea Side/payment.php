<?php
session_start();

if (!isset($_SESSION['reservation_id'])) {
    header("Location: reservation.php");
    exit();
}

$name = $_SESSION['reservation_name'];
$date = $_SESSION['reservation_date'];
$time = $_SESSION['reservation_time'];
$guests = $_SESSION['reservation_guests'];
$amount = $guests * 249;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment | Seaside Restaurant</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #2e6193, #2e938e);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .payment-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
        }

        h1, h2 {
            text-align: center;
            color: #2e6193;
        }

        .method {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border 0.3s;
        }

        .method:hover, .method.selected {
            border-color: #2e6193;
        }

        .method img {
            width: 40px;
            margin-right: 10px;
        }

        .form-section {
            display: none;
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .form-section.active {
            display: block;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #2e6193;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background-color: #244f78;
        }
    </style>
</head>
<body>
<div class="payment-container">
    <h1>Complete Your Payment</h1>
    <p><strong>Name:</strong> <?= htmlspecialchars($name) ?></p>
    <p><strong>Date:</strong> <?= htmlspecialchars($date) ?></p>
    <p><strong>Time:</strong> <?= htmlspecialchars($time) ?></p>
    <p><strong>Guests:</strong> <?= $guests ?></p>
    <p><strong>Total:</strong> PHP <?= number_format($amount, 2) ?></p>

    <h2>Select Payment Method</h2>

    <div class="method" onclick="selectMethod('gcash')">
        <img src="gcash.png" alt="GCash"> GCash
    </div>
    <div class="method" onclick="selectMethod('paypal')">
        <img src="paypal.png" alt="PayPal"> PayPal
    </div>
    <div class="method" onclick="selectMethod('card')">
        <img src="credit.png" alt="Card"> Credit/Debit Card
    </div>

    <form action="payment_success.php" method="post">
        <div class="form-section" id="gcash">
            <h3>GCash Details</h3>
            <input type="text" name="gcash_name" placeholder="Full Name">
            <input type="text" name="gcash_number" placeholder="Mobile Number">
        </div>

        <div class="form-section" id="paypal">
            <h3>PayPal Details</h3>
            <input type="email" name="paypal_email" placeholder="PayPal Email">
            <input type="text" name="paypal_name" placeholder="Full Name">
        </div>

        <div class="form-section" id="card">
            <h3>Card Details</h3>
            <input type="text" name="card_number" placeholder="Card Number">
            <input type="text" name="card_expiry" placeholder="Expiration Date (MM/YY)">
            <input type="text" name="card_cvv" placeholder="CVV">
            <input type="text" name="card_name" placeholder="Name on Card">
        </div>

        <button type="submit">Confirm Payment</button>
    </form>
</div>

<script>
    function selectMethod(method) {
        document.querySelectorAll('.form-section').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.method').forEach(el => el.classList.remove('selected'));
        document.getElementById(method).classList.add('active');
        event.currentTarget.classList.add('selected');
    }
</script>
</body>
</html>

                
