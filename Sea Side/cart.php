<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Check if reserved and paid
$hasReservation = isset($_SESSION['reservation_id']);
$hasPaid = isset($_SESSION['has_paid']) && $_SESSION['has_paid'] === true;



// Handle remove item
if (isset($_POST['remove_item'])) {
    $remove_id = $_POST['remove_id'];
    foreach ($_SESSION['cart'] as $index => $item) {
        if ($item['id'] == $remove_id) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index
            break;
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle clear cart
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart | Seaside Restaurant</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #e0f7fa, #b2ebf2);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #006064;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        table th {
            background: #e0f2f1;
            color: #00695c;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .actions form button {
            background: #d32f2f;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .actions form button:hover {
            background: #b71c1c;
            transform: scale(1.05);
        }

        .remove-btn {
            background: #ff7043;
        }

        .remove-btn:hover {
            background: #d84315;
        }

        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Your Cart</h1>
    <?php if (!$hasReservation || !$hasPaid): ?>
    <p style="text-align: center; color: #c62828;">
        You need to complete your reservation and payment to access the cart.
    </p>
    <div style="text-align: center; margin-top: 20px;">
        <a href="reservation.php"><button>→ Make a Reservation</button></a>
    </div>
<?php elseif (!empty($_SESSION['cart'])): ?>
    <!-- (The rest of your cart display and actions here...) -->
    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $total += $item['price'];
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>PHP <?= number_format($item['price'], 2) ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="remove_id" value="<?= $item['id'] ?>">
                            <button type="submit" name="remove_item" class="remove-btn">🗑️ Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="total">
            Total: PHP <?= number_format($total, 2) ?>
        </div>

        <div class="actions">
            <form method="POST">
                <button type="submit" name="clear_cart">❌ Clear Cart</button>
            </form>
            <a href="menu.php"><button>← Back to Menu</button></a>
        </div>
    <?php else: ?>
        <p style="text-align: center;">Your cart is empty.</p>
        <div style="text-align: center; margin-top: 20px;">
            <a href="menu.php"><button>← Browse Menu</button></a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
