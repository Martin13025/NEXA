<?php
$products = json_decode(file_get_contents('data/products.json'), true);
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Интернет-магазин NEXA</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <h1>NEXA</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="cart.php">Корзина</a> |
            <a href="profile.php">Личный кабинет</a>
        </nav>
    </header>

    <main>
        <h2>Товары</h2>
        <div class="products">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Цена: <?php echo htmlspecialchars($product['price']); ?> руб.</p>
                    <button class="add-to-cart" data-id="<?php echo $product['id']; ?>">Добавить в корзину</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <footer>
        <div class="footer-container">
            <div class="footer-info">
                <h3 class="footer-title">NEXA</h3>
                <p class="footer-description">Современный интернет-магазин стильных товаров</p>
                <div class="contact-info">
                    <p>✉️ <a href="mailto:info@nexa.com">info@nexa.com</a></p>
                </div>
            </div>
            <div class="social-icons">
                <a href="#" aria-label="Instagram" class="social-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7.75 2A5.75 5.75 0 002 7.75v8.5A5.75 5.75 0 007.75 22h8.5A5.75 5.75 0 0022 16.25v-8.5A5.75 5.75 0 0016.25 2h-8.5zM12 7a5 5 0 110 10 5 5 0 010-10zm6.5-1.75a1.25 1.25 0 11-2.5 0 1.25 1.25 0 012.5 0z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </a>
                <a href="#" aria-label="Facebook" class="social-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54v-2.89h2.54V9.845c0-2.507 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.462h-1.26c-1.243 0-1.63.771-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.987C18.343 21.128 22 16.991 22 12z" />
                    </svg>
                </a>
                <a href="#" aria-label="Twitter" class="social-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 01-3.14.86 4.48 4.48 0 001.98-2.48 9.18 9.18 0 01-2.91 1.11 4.52 4.52 0 00-7.69 4.12 12.84 12.84 0 01-9.32-4.72 4.5 4.5 0 001.39 6.03 4.48 4.48 0 01-2.05-.56v.06a4.52 4.52 0 003.63 4.43 4.5 4.5 0 01-2.04.08 4.53 4.53 0 004.22 3.14A9.05 9.05 0 012 19.54 12.76 12.76 0 008.29 21c7.54 0 11.67-6.26 11.67-11.67 0-.18 0-.35-.01-.53A8.18 8.18 0 0023 3z" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>


    <script src="js/main.js"></script>
</body>

</html>