<?php
session_start();

/*
echo '<pre>Cookie cart: ' . ($_COOKIE['cart'] ?? 'нет') . "</pre>";

$cart = json_decode($_COOKIE['cart'] ?? '{}', true);

echo '<pre>Decoded cart: ';
print_r($cart);
echo '</pre>';*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $removeId = $_POST['remove_id'];
    $cart = json_decode($_COOKIE['cart'] ?? '{}', true);
    if (isset($cart[$removeId])) {
        unset($cart[$removeId]);
        setcookie('cart', json_encode($cart), time() + 7 * 24 * 60 * 60, '/');
    }

    header('Location: cart.php');
    exit;
}

$products = json_decode(file_get_contents('data/products.json'), true);
$cart = json_decode($_COOKIE['cart'] ?? '{}', true);
if (!is_array($cart)) $cart = [];

$total = 0;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Корзина</title>
    <link rel="stylesheet" href="css/style.css" />
    <script>
        function removeFromCart(id) {
            let cart = JSON.parse(localStorage.getItem('cart')) || {};
            delete cart[id];
            localStorage.setItem('cart', JSON.stringify(cart));
            location.reload();
        }
    </script>
</head>

<body>
    <header>
        <h1>Корзина</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="profile.php">Личный кабинет</a>
        </nav>
    </header>
    <main>
        <?php if (empty($cart)): ?>
            <p>Корзина пуста.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Итого</th>
                        <th>Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $productId => $quantity): ?>
                        <?php
                        $product = null;
                        foreach ($products as $p) {
                            if ($p['id'] == $productId) {
                                $product = $p;
                                break;
                            }
                        }
                        if (!$product) continue;
                        $itemTotal = $product['price'] * $quantity;
                        $total += $itemTotal;
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td><?php echo $product['price']; ?> руб.</td>
                            <td><?php echo $itemTotal; ?> руб.</td>
                            <td>
                                <form method="POST" action="cart.php" style="display:inline;">
                                    <input type="hidden" name="remove_id" value="<?php echo htmlspecialchars($productId); ?>">
                                    <button type="submit" type="button">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Итого:</strong></td>
                        <td colspan="2"><strong><?php echo $total; ?> руб.</strong></td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <a href="order.php"><button>Оформить заказ</button></a>
        <?php endif; ?>
    </main>
</body>

</html>