<?php
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Подключение к базе данных MySQL
    $mysqli = new mysqli("localhost", "root", "", "data");

    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    } else {
        // Подготовка и выполнение SQL-запроса для проверки учетных данных пользователя
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            // Верные учетные данные. Создание сессии и перенаправление на страницу пользователя
            $_SESSION['username'] = $username;
            header("Location: user.php");
            exit();
        } else {
            // Неправильные учетные данные. Вывод ошибки.
            $error_message = "Неправильный логин или пароль.";
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Стартовый экран</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="animate.min.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container animate__animated animate__fadeIn">
        <h1>Авторизация</h1>
        <form method="POST" action="index.php" class='form'>
            <input type="text" name="username" placeholder="Логин">
            <input type="password" name="password" placeholder="Пароль">
            <input type="submit" value="Войти" class="animate__animated animate__fadeInUp">
            <?php if (isset($error_message)): ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
