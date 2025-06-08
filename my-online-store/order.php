<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$products = json_decode(file_get_contents('data/products.json'), true);
$ordersFile = 'data/orders.json';
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];

$cart = json_decode($_COOKIE['cart'] ?? '{}', true);
if (!is_array($cart) || empty($cart)) {
    die('Корзина пуста.');
}

$total = 0;
foreach ($cart as $pid => $qty) {
    foreach ($products as $p) {
        if ($p['id'] == $pid) {
            $total += $p['price'] * $qty;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newOrder = [
        'id' => count($orders) ? max(array_column($orders, 'id')) + 1 : 1,
        'user_id' => $_SESSION['user']['id'],
        'items' => [],
        'total' => $total,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    foreach ($cart as $pid => $qty) {
        $newOrder['items'][] = [
            'product_id' => $pid,
            'quantity' => $qty,
        ];
    }

    $orders[] = $newOrder;
    file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));

    setcookie('cart', '', time() - 3600, '/');
    echo 'Спасибо за заказ! <a href="index.php">Вернуться на главную</a>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Оформление заказа</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <h1>Оформление заказа</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="cart.php">Корзина</a> |
            <a href="profile.php">Личный кабинет</a> |
            <a href="logout.php">Выход</a>
        </nav>
    </header>
    <main>
        <h2>Итог: <?php echo $total; ?> руб.</h2>
        <form method="post" action="order.php">
            <button type="submit">Подтвердить заказ</button>
        </form>
    </main>
</body>

</html>