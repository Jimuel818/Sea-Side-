<?php
session_start();
$error = [
    'login' => $_SESSION['login_error'] ?? '',
    'register' => $_SESSION['register_error'] ?? '',
    'register_success' => $_SESSION['register_success'] ?? ''
];
$activeForm = $_SESSION['active_form'] ?? 'login';

function showError($error){
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

function isActiveForm($formName, $activeForm){
    return $formName === $activeForm ? 'active' : '';
}

$conn = new mysqli("localhost", "root", "", "sea_side_sql");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seaside Restaurant | Welcome</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body{
            display:flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2e6193, #2e8e93);
            color: black;
        }
        .container {
            margin: 0 15px;
        }

        .form-box {
            width: 100%;
            max-width: 450px;
            padding:30px ;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px #553434;
            display: none;
        }
        .form-box.active {
            display:block;
        }

        h2{
            font-size: 34px;
            text-align: center;
            margin-bottom: 20px;
        }

        input,
        select{
            width: 100%;
            padding: 12px;
            background: #fff;
            border-radius: 10px;
            border: none;
            outline: none;
            font-size: 16px;
            color: rgb(0, 0, 0);
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #553434;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: rgb(255, 255, 255);
            font-weight: 500;
            margin-bottom: 20px;
            transition: 0.5s;
        }

        button:hover {
            background: rgb(200, 6, 6);
        }

        .logout-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            max-width: 600px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        .logout-box h2 {
            color: #2e6193;
        }

        .reservation-status {
            margin-top: 20px;
            background: #e6f3f2;
            border-radius: 10px;
            padding: 15px;
            text-align: left;
            animation: fadeInUp 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        p {
            font-size: 14.5px;
            text-align: center;
            margin-bottom: 10px;
        }

        p a {
            color: rgb(0, 0, 0);
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }

        .error-message {
            padding: 12px;
            background: #fff;
            border-radius: 6px;
            font-size: 16px;
            color: #553434;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="logout-box">
            <h2>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>
            <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['email']) ?></strong></p>
            <form action="logout.php" method="post">
                <button type="submit" name="logout">Log Out</button>
            </form>

            <div class="reservation-status">
                <h3>Your Recent Reservation:</h3>
                <?php
                $uid = $_SESSION['user_id'];
                $res = $conn->query("SELECT * FROM reservations WHERE user_id = $uid ORDER BY created_at DESC LIMIT 1");
                if ($res && $res->num_rows > 0) {
                    $r = $res->fetch_assoc();
                    echo "<p><strong>Date:</strong> " . $r['reservation_date'] . "</p>";
                    echo "<p><strong>Time:</strong> " . $r['reservation_time'] . "</p>";
                    echo "<p><strong>Guests:</strong> " . $r['guests'] . "</p>";
                    echo "<p><strong>Status:</strong> Confirmed</p>";
                } else {
                    echo "<p>You have no recent reservations.</p>";
                }
                ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Login Form -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Log in</h2>
                <?= showError($error['login']); ?>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Log in</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?= showError($error['register']); ?>
                <?= showError($error['register_success']); ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Log in</a></p>
            </form>
        </div>
    <?php endif; ?>
</div>

<script>
function showForm(formId) {
    document.querySelectorAll('.form-box').forEach(form => {
        form.classList.remove('active');
    });
    document.getElementById(formId).classList.add('active');
}
</script>
</body>
</html>

