<?php
session_start();

// Проверка, вошел ли пользователь в систему
if (!isset($_SESSION['username'])) {
    header('Location: index.php');  // Перенаправление на страницу входа, если пользователь не вошел в систему
    exit();
}

// Включение кода подключения к базе данных
include('connection.php');

// Получение данных из базы данных для отображения выбора автомобиля с available_count > 0
$sql = 'SELECT car_name, rental_rate FROM cars WHERE available_count > 0';
$result = mysqli_query($con, $sql);

// Проверка ошибок в запросе к базе данных
if (!$result) {
    die('Ошибка запроса к базе данных: ' . mysqli_error($con));
}

// Закрытие подключения к базе данных
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="ru">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выбор автомобиля</title>
</head>
<body>
    <h1>Выберите свой автомобиль</h1>

    <form action="rental_process.php" method="post">
        <?php
        // Включение скрытых полей из предыдущей формы
        if (isset($_POST['pickupLocation']) && isset($_POST['pickupDateTime']) && isset($_POST['dropoffDateTime'])) {
            echo '<input type="hidden" name="pickupLocation" value="' . $_POST['pickupLocation'] . '">';
            echo '<input type="hidden" name="pickupDateTime" value="' . $_POST['pickupDateTime'] . '">';
            echo '<input type="hidden" name="dropoffDateTime" value="' . $_POST['dropoffDateTime'] . '">';
            echo '<input type="hidden" name="differentDropoff" value="0">';
            echo '<input type="hidden" name="dropoffLocation" value="' . $_POST['pickupLocation'] . '">';
        }

        if (isset($_POST['differentDropoff']) && isset($_POST['dropoffLocation'])) {
            echo '<input type="hidden" name="differentDropoff" value="' . $_POST['differentDropoff'] . '">';
            echo '<input type="hidden" name="dropoffLocation" value="' . $_POST['dropoffLocation'] . '">';
        }
        ?>

        <label for="selectedCar">Выберите автомобиль:</label>
        <select id="selectedCar" name="selectedCar" required>
            <?php
            // Заполнение вариантов выпадающего списка
            while ($row = mysqli_fetch_assoc($result)) {
                $car_name = $row['car_name'];
                $rental_rate = $row['rental_rate'];
                echo '<option value="' . $car_name . '|' . $rental_rate . '">' . $car_name . ' - $' . $rental_rate . ' в день' . '</option>';
            }
            ?>
        </select>

        <button type="submit">Далее</button>
    </form>
</body>
</html>
