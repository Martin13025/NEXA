<?php
session_start();

function loadUsers()
{
    $file = 'data/users.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $users = loadUsers();
    $userFound = null;
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $userFound = $user;
            break;
        }
    }

    if ($userFound) {
        $_SESSION['user'] = $userFound;
        header('Location: profile.php');
        exit;
    } else {
        $errors[] = 'Неверный email или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Вход</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <h1>Авторизация</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="register.php">Регистрация</a>
        </nav>
    </header>
    <main>
        <?php if ($errors): ?>
            <div style="color: red; margin-bottom: 10px;">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label>
                Email:<br />
                <input type="email" name="email" required />
            </label><br /><br />
            <label>
                Пароль:<br />
                <input type="password" name="password" required />
            </label><br /><br />
            <button type="submit">Войти</button>
        </form>
    </main>
</body>

</html>