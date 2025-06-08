<?php
session_start();

function loadUsers()
{
    $file = 'data/users.json';
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true);
}

function saveUsers($users)
{
    file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email';
    }
    if (strlen($name) < 2) {
        $errors[] = 'Имя слишком короткое';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Пароль должен быть не менее 6 символов';
    }
    if ($password !== $password_confirm) {
        $errors[] = 'Пароли не совпадают';
    }

    $users = loadUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $errors[] = 'Пользователь с таким email уже существует';
            break;
        }
    }

    if (empty($errors)) {
        $newUser = [
            'id' => count($users) ? max(array_column($users, 'id')) + 1 : 1,
            'email' => $email,
            'name' => $name,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];
        $users[] = $newUser;
        saveUsers($users);
        $_SESSION['user'] = $newUser;
        header('Location: profile.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <h1>Регистрация</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="login.php">Вход</a>
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
        <form method="post" action="register.php">
            <label>
                Email:<br />
                <input type="email" name="email" required />
            </label><br /><br />
            <label>
                Имя:<br />
                <input type="text" name="name" required />
            </label><br /><br />
            <label>
                Пароль:<br />
                <input type="password" name="password" required />
            </label><br /><br />
            <label>
                Подтвердите пароль:<br />
                <input type="password" name="password_confirm" required />
            </label><br /><br />
            <button type="submit">Зарегистрироваться</button>
        </form>
    </main>
</body>

</html>