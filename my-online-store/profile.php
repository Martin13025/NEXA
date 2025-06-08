<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email';
    }

    if (strlen($name) < 2) {
        $errors[] = 'Имя слишком короткое';
    }

    if (empty($errors)) {
        $users = loadUsers();
        foreach ($users as &$u) {
            if ($u['id'] == $user['id']) {
                $u['name'] = $name;
                $u['email'] = $email;
                $user = $u;
                $_SESSION['user'] = $user;
                $success = 'Данные обновлены';
                break;
            }
        }
        saveUsers($users);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <h1>Личный кабинет</h1>
        <nav>
            <a href="index.php">Главная</a> |
            <a href="cart.php">Корзина</a> |
            <a href="logout.php">Выход</a>
        </nav>
    </header>
    <main>
        <?php if ($errors): ?>
            <div style="color: red; margin-bottom: 10px;">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php elseif ($success): ?>
            <div style="color: green; margin-bottom: 10px;">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="profile.php">
            <label>
                Имя:<br />
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required />
            </label><br /><br />
            <label>
                Email:<br />
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required />
            </label><br /><br />
            <button type="submit">Обновить</button>
        </form>
    </main>
</body>

</html>