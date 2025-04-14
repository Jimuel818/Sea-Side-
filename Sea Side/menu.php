<?php
session_start();

if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit();
    }

    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $_SESSION['cart'][] = [
        'id' => $item_id,
        'name' => $item_name,
        'price' => $item_price
    ];

    header("Location: menu.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu | Seaside Restaurant</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: linear-gradient(to bottom right, #e0f7fa, #80deea);
        }

        header {
            background: #006064;
            padding: 20px;
            color: white;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .cart {
            text-align: right;
            padding: 10px 20px;
            background: #004d40;
        }

        .cart a {
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
        }

        .menu-title {
            text-align: center;
            margin: 30px 0 10px;
            font-size: 32px;
            color: #004d40;
        }

        .menu-categories {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .menu-categories button {
            background: #004d40;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .menu-categories button:hover {
            background: #00796b;
            transform: scale(1.05);
        }

        .menu-list {
            max-width: 1000px;
            margin: auto;
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .menu-list.active {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: slideUp 0.3s ease;
        }

        .menu-item h3 {
            margin: 0;
            font-size: 20px;
            color: #00695c;
        }

        .menu-item p {
            margin: 8px 0;
        }

        .menu-item form button {
            background: #00838f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .menu-item form button:hover {
            background: #006064;
            transform: scale(1.05);
        }

        @keyframes slideUp {
            from { transform: translateY(10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .logo img {
    width: 50px;
    height: 50px;
    margin-left: 20px;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
}

nav ul li {
    margin: 0 15px;
    list-style: none;
    display: inline-block;
}

nav ul li a {
    color: rgb(255, 255, 255);
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    transition: all ease 0.5s;
}

nav ul li a:hover {
    background-color: #000b41;
}
h2 {
    font-size: 20px;
    font-style: bold;
    color: #e6e6e6;
    font-family: Copperplate, Papyrus, fantasy;
}
    </style>
</head>
<body>

<header>
        <div class="logo">
        <a href="user_page.php">
        <img src="logo.png" alt="Restaurant logo">
        </div>  
        <nav>   
            <ul>
                <li><a href="about.php">About</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="reservation.php">Reservation</a></li>
                <li><a href="gallery.php">Gallery</a></li>
                <li><a href="index.php">Login</a></li>
            </ul>
        </nav>
    </header>

<div class="cart">
    <a href="<?= isset($_SESSION['user_id']) ? 'cart.php' : 'index.php' ?>">
        🛒 View Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)
    </a>
</div>

<section id="menu">
    <h1 class="menu-title">MENU</h1>

    <div class="menu-categories">
        <?php
        $menu_json = file_get_contents("menu_data.json");
        $categories = json_decode($menu_json, true);
        foreach ($categories as $category => $items) {
            echo "<button onclick=\"showMenu('$category')\">$category</button>";
        }
        ?>
    </div>

    <?php foreach ($categories as $category => $items): ?>
        <div class="menu-list" id="menu-<?= $category ?>">
            <?php foreach ($items as $item): ?>
                <div class="menu-item">
                    <h3><?= $item['name'] ?></h3>
                    <p>PHP <?= isset($item['price']) ? number_format($item['price'], 2) : '—' ?></p>
                    <?php if ($category !== "Beverages" && isset($item['price'])): ?>
                        <form method="POST">
                            <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                            <input type="hidden" name="item_name" value="<?= $item['name'] ?>">
                            <input type="hidden" name="item_price" value="<?= $item['price'] ?>">
                            <button type="submit" name="add_to_cart">Add to Cart</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>

<script>
    function showMenu(category) {
        document.querySelectorAll('.menu-list').forEach(list => {
            list.classList.remove('active');
        });
        document.getElementById('menu-' + category).classList.add('active');
    }

    // Show first category on load
    window.onload = function () {
        const firstList = document.querySelector('.menu-list');
        if (firstList) firstList.classList.add('active');
    };
</script>

</body>
</html>


