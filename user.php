<?php
session_start();

if (!isset($_SESSION['username'])) {
    // Если пользователь не авторизован, перенаправляем его на стартовый экран
    header("Location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    // Если пользователь нажал на кнопку выхода, удаляем сессию и перенаправляем на стартовый экран
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}

// Подключение к базе данных MySQL

$mysqli = new mysqli("localhost", "root", "", "data");

if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {
    $username = $_SESSION['username'];

    // Подготовка и выполнение SQL-запроса для получения информации о пользователе
    $stmt = $mysqli->prepare("SELECT name, photo, birthdate FROM users WHERE username = ?");

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $photo = $row['photo'];
        $dateOfBirth = $row['birthdate'];
    } else {
        // Обработка ошибки, если информация о пользователе не найдена
        echo "Ошибка: Информация о пользователе не найдена.";
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Страница пользователя</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="animate.min.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="container animate__animated animate__zoomIn">
        <h1>Привет, <?php echo $name; ?>!</h1>
        <img src="<?php echo $photo; ?>" alt="Фото пользователя" class="animate__animated animate__fadeInUp">
        <p>Дата рождения: <?php echo $dateOfBirth; ?></p>
        <a href="?logout=1" class="animate__animated animate__fadeInUp">Выйти</a>
    </div>
</body>
</html>
