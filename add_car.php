<?php
// Включение кода подключения к базе данных
include('connection.php');

session_start();
$role = $_SESSION['role'];
if (isset($_SESSION['username']) and $role == 1) {
    $isAdmin = true; // Измените это в соответствии с вашей логикой аутентификации
}

if (!$isAdmin) {
    // Перенаправление или вывод сообщения об ошибке, если пользователь не является администратором
    header("Location: index.php");
    exit();
}

// Проверка, отправлена ли форма на добавление нового автомобиля
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addCar'])) {
    // Получение входных данных формы
    $carName = mysqli_real_escape_string($con, $_POST['car_name']);
    $rentalRate = mysqli_real_escape_string($con, $_POST['rental_rate']);
    $availableCars = mysqli_real_escape_string($con, $_POST['available_cars']);

    // Вставка нового автомобиля в таблицу cars
    $insertQuery = "INSERT INTO cars (car_name, rental_rate, available_count) VALUES ('$carName', '$rentalRate', $availableCars)";

    if (mysqli_query($con, $insertQuery)) {
        echo "Автомобиль '$carName' успешно добавлен!";
    } else {
        echo "Ошибка: " . mysqli_error($con);
    }
}

// Проверка, отправлена ли форма на удаление существующего автомобиля
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteCar'])) {
    $carToDelete = mysqli_real_escape_string($con, $_POST['carToDelete']);

    // Удаление
    $deleteQuery = "DELETE FROM cars WHERE car_name = '$carToDelete'";
    
    if (mysqli_query($con, $deleteQuery)) {
        echo "Автомобиль '$carToDelete' успешно удален!";
    } else {
        echo "Ошибка: " . mysqli_error($con);
    }
}

// Проверка, отправлена ли форма на редактирование существующего автомобиля
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editCar'])) {
    // Получение входных данных формы для редактирования
    $carToEdit = mysqli_real_escape_string($con, $_POST['carToEdit']);
    $newCarName = mysqli_real_escape_string($con, $_POST['new_car_name']);
    $newRentalRate = mysqli_real_escape_string($con, $_POST['new_rental_rate']);
    $newAvailableCars = mysqli_real_escape_string($con, $_POST['new_available_cars']);

    // Обновление информации о существующем автомобиле
    $updateQuery = "UPDATE cars SET car_name = '$newCarName', rental_rate = '$newRentalRate', available_count = $newAvailableCars WHERE car_name = '$carToEdit'";
    
    if (mysqli_query($con, $updateQuery)) {
        echo "Информация об автомобиле '$carToEdit' успешно обновлена!";
    } else {
        echo "Ошибка: " . mysqli_error($con);
    }
}

// Получение данных из базы данных для формы удаления
$sql = "SELECT car_name FROM cars";
$result = mysqli_query($con, $sql);

// Закрытие подключения к базе данных
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление или удаление автомобиля</title>
    <!-- Добавление ссылки на Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
            </div>
    <div class="col-md-3 text-end">
        <a  href="index.php" class="btn btn-primary me-2">Назад</a>
    </div>
    <div class="container mt-4">
        <h1>Добавление нового автомобиля</h1>

        <form action="" method="post">
            <label for="car_name">Название автомобиля:</label>
            <input type="text" id="car_name" name="car_name" required>

            <label for="rental_rate">Тариф аренды:</label>
            <input type="number" id="rental_rate" name="rental_rate" required>

            <label for="rental_rate">Доступные автомобили:</label>
            <input type="number" id="available_cars" name="available_cars" required>

            <button type="submit" name="addCar" class="btn btn-success">Добавить автомобиль</button>
        </form>
    </div>

    <div class="container mt-4">
        <h2>Удаление существующего автомобиля</h2>
        <form action="" method="post">
            <label for="carToDelete">Выберите автомобиль для удаления:</label>
            <select id="carToDelete" name="carToDelete" required>
                <?php
                // Заполнение вариантов выпадающего списка для формы удаления
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['car_name'] . '">' . $row['car_name'] . '</option>';
                }
                mysqli_data_seek($result, 0);
                ?>
            </select>

            <button type="submit" name="deleteCar" class="btn btn-danger">Удалить автомобиль</button>
        </form>
    </div>

    <div class="container mt-4">
            <h2>Редактирование существующего автомобиля</h2>
            <!-- Форма редактирования автомобиля -->
            <form action="" method="post">
                <label for="carToEdit">Выберите автомобиль для редактирования:</label>
                <select id="carToEdit" name="carToEdit" required>
                    <?php
                    // Заполнение вариантов выпадающего списка для формы редактирования
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['car_name'] . '">' . $row['car_name'] . '</option>';
                    }
                    ?>
                </select>
                <label for="new_car_name">Новое название автомобиля:</label>
                <input type="text" id="new_car_name" name="new_car_name" required>

                <label for="new_rental_rate">Новый тариф аренды:</label>
                <input type="number" id="new_rental_rate" name="new_rental_rate" required>

                <label for="new_available_cars">Новое количество доступных автомобилей:</label>
                <input type="number" id="new_available_cars" name="new_available_cars" required>

                <button type="submit" name="editCar" class="btn btn-warning">Редактировать автомобиль</button>
            </form>
        </div>
    <!-- Добавление Bootstrap JS и Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>