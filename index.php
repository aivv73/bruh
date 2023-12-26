<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <header>
        <link href="headers.css" rel="stylesheet">
    </header>
    
    <main>
    <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
            </div>
<?php
session_start();
$role = $_SESSION['role'];
if (isset($_SESSION['username']) and $role == 0)
    echo '            <div class="col-md-3 text-end">
        <a href="logout.php" class="btn btn-danger">Выйти</a>
    </div>';
else if (isset($_SESSION['username']) and $role == 1)
    echo '            <div class="col-md-3 text-end">
        <a  href="add_car.php" class="btn btn-outline-primary">Управление автомобилями</a>
        <a href="logout.php" class="btn btn-danger">Выйти</a>
    </div>';
else
    echo ' <div class="col-md-3 text-end"> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal"> Войти </button>
         <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signupModal"> Зарегистрироваться </button>
        </div>'
    ?>

            </header>
        </div>
    <?php
        // Check if the user is logged in
        session_start();
        $role = $_SESSION['role'];

        include ('connection.php');

        $checkExpiredQuery = "SELECT * FROM rentals WHERE username = '{$_SESSION['username']}' AND status = 'expired'";
        $expiredResult = mysqli_query($con, $checkExpiredQuery);

        if ($expiredResult && mysqli_num_rows($expiredResult) > 0) {
            echo '<div class="alert alert-warning" role="alert">У вас истек срок аренды. Пожалуйста, проверьте историю аренды.</div>';
        }

        if (isset($_SESSION['username']) and ($role == 0)) {
            ?>
            <form action="car_selection.php" method="post">
                <!-- Your form fields here -->

                <div class="container mt-5">
                <form action="rental_process.php" method="post">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="pickupLocation" class="form-label">Место получения:</label>
            <input type="text" class="form-control" id="pickupLocation" name="pickupLocation" required>
            <div class="invalid-feedback">
                Требуется указать допустимое место получения.
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="differentDropoff" class="form-check-label">Указать другое место возврата</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="differentDropoff" name="differentDropoff">
            </div>
        </div>

        <div class="col-md-6 mb-3" id="differentDropoffLocation" style="display: none;">
            <label for="dropoffLocation" class="form-label">Место возврата:</label>
            <input type="text" class="form-control" id="dropoffLocation" name="dropoffLocation">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="pickupDateTime" class="form-label">Дата и время получения:</label>
            <input type="datetime-local" class="form-control" id="pickupDateTime" name="pickupDateTime" required>
        </div>

        <script>
            // Получить текущую дату и время в формате, необходимом для ввода datetime-local
            var currentDate = new Date().toISOString().slice(0, 16);

            // Установить атрибут min для ввода pickupDateTime в текущую дату и время
            document.getElementById('pickupDateTime').min = currentDate;

            // Добавить прослушиватель событий pickupDateTime для обновления атрибута min у dropoffDateTime
            document.getElementById('pickupDateTime').addEventListener('input', function() {
                // Получить выбранную дату и время из pickupDateTime
                var selectedDateTime = document.getElementById('pickupDateTime').value;

                // Установить атрибут min у dropoffDateTime в выбранную дату и время из pickupDateTime
                document.getElementById('dropoffDateTime').min = selectedDateTime;
            });
        </script>

        <div class="col-md-6 mb-3">
            <label for="dropoffDateTime" class="form-label">Дата и время возврата:</label>
            <input type="datetime-local" class="form-control" id="dropoffDateTime" name="dropoffDateTime" required>
        </div>
    </div>

    <button class="btn btn-primary btn-lg" type="submit">Далее: Выбрать автомобиль</button>
</form>
</div>

<script>
    // Function to toggle visibility of different drop-off location input
    function toggleDifferentDropoffLocation() {
        var differentDropoffLocation = document.getElementById('differentDropoffLocation');
        var differentDropoffCheckbox = document.getElementById('differentDropoff');

        // Check the initial state of the checkbox
        differentDropoffLocation.style.display = differentDropoffCheckbox.checked ? 'block' : 'none';

        // Add an event listener to the checkbox to update the input visibility
        differentDropoffCheckbox.addEventListener('change', function () {
            differentDropoffLocation.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Call the function when the page loads
    window.onload = toggleDifferentDropoffLocation;
</script>

            <?php
        } else if (!isset($_SESSION['username'])) {
            echo '<p>Пожалуйста войдите что бы увидеть форму аренды.</p>';
        }
                    ?>
                <?php
                    // Include your database connection code
                    include ('connection.php');

                    // Check if the role is equal to 1.
                    if (isset($_SESSION['username']) and $role == 1) {
                        // The user is an administrator.
                        // Get the user's rental history from the database
                        $getRentalsQuery = 'SELECT * FROM rentals ORDER BY dropoff_datetime DESC';
                        $rentalsResult = mysqli_query($con, $getRentalsQuery);

                        // Check if the query was successful
                        if ($rentalsResult) {
                            // Fetch the results as an associative array
                            $rentals = mysqli_fetch_all($rentalsResult, MYSQLI_ASSOC);

                            // Join the rentals table with the cars table
                            $joinQuery = "SELECT rentals.*, cars.car_name, cars.rental_rate FROM rentals JOIN cars ON rentals.car_id = cars.id";
                            $joinResult = mysqli_query($con, $joinQuery);

                            // Check if the join query was successful
                            if ($joinResult) {
                                // Fetch the results as an associative array
                                $rentals = mysqli_fetch_all($joinResult, MYSQLI_ASSOC);
                                // Separate rentals based on status
                                $expiredRentals = [];
                                $activeRentals = [];
                                $returnedRentals = [];

                                foreach ($rentals as $rental) {
                                    switch ($rental['status']) {
                                        case 'expired':
                                            $expiredRentals[] = $rental;
                                            break;
                                        case 'active':
                                            $activeRentals[] = $rental;
                                            break;
                                        case 'returned':
                                            $returnedRentals[] = $rental;
                                            break;
                                    }
                                }

                                // Merge the arrays in the desired order
                                $sortedRentals = array_merge($expiredRentals, $activeRentals, $returnedRentals);

                                echo '<div class="container mt-4">';

                                foreach ($sortedRentals as $rental) {

                                    $total_rent_cost = $rental['rental_days'] * $rental['rental_rate'];
                                    $isExpired = ($rental['status'] === 'expired');
                                    $cardClass = $isExpired ? 'bg-warning' : '';
                                    $liClass = $isExpired ? 'bg-warning' : '';
                                    echo '<div class="card mt-3 ' . $cardClass . '">';
                                    echo '<div class="card-body ">';
                                    
                                    echo '<h5 class="card-title">Подробности аренды</h5>';
                                    echo '<ul class="list-group list-group-flush ">';
                                    echo "<li class='list-group-item " . $liClass . "'>Состояние: {$rental['status']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Имя пользователя: {$rental['username']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Место получения: {$rental['pickup_location']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Место возврата: {$rental['dropoff_location']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Дата и время получения: {$rental['pickup_datetime']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Дата и время возврата: {$rental['dropoff_datetime']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Название авто: {$rental['car_name']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Дней аренды: {$rental['rental_days']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Общая стоимость аренды: \${$total_rent_cost}</li>";
                                    echo '</ul>';

                                    // Assuming $rental is your rental data
                                    if ($rental['status'] != 'returned') {
                                        echo '<button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#markReturnedModal' . $rental['id'] . '">';
                                        echo ' Пометить машину как возвращённую';
                                        echo '</button>';
                                    
                                        // Modal for each rental
                                        echo '<div class="modal fade" id="markReturnedModal' . $rental['id'] . '" tabindex="-1" aria-labelledby="markReturnedModalLabel' . $rental['id'] . '" aria-hidden="true">';
                                        echo '<div class="modal-dialog">';
                                        echo '<div class="modal-content">';
                                        echo '<div class="modal-header">';
                                        echo '<h5 class="modal-title" id="markReturnedModalLabel' . $rental['id'] . '">Пометить машину как возвращённую</h5>';
                                        echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                        echo '</div>';
                                        echo '<div class="modal-body">';
                                        echo '<p>Вы увренны что хотите пометить машину как возвращённую.</p>';
                                        echo '</div>';
                                        echo '<div class="modal-footer">';
                                        echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>';
                                        echo '<form action="update_status.php" method="post">';
                                        echo '<input type="hidden" name="rental_id" value="' . $rental['id'] . '">';
                                        echo '<button type="submit" class="btn btn-primary">Пометить машину как возвращённую.</button>';
                                        echo '</form>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    
                                    // Button to delete the rental
                                    echo '<button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#deleteModal">Удалить аренду</button>';
                                    // Delete Modal
                                    echo '<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">';
                                    echo '<div class="modal-dialog">';
                                    echo '<div class="modal-content">';
                                    echo '<div class="modal-header">';
                                    echo '<h5 class="modal-title" id="deleteModalLabel">Подтвердить</h5>';
                                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                    echo '</div>';
                                    echo '<div class="modal-body">';
                                    echo '<p>Вы уверены, что хотите удалить эту аренду?</p>';
                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>';
                                    echo '<form action="delete_rental.php" method="post">';
                                    echo '<input type="hidden" name="rental_id" value="' . $rental['id'] . '">';
                                    echo '<button type="submit" class="btn btn-danger">Удалить аренду</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';

                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                // Display an error message if the join query failed
                                echo 'Error: ' . mysqli_error($con);
                            }
                        } else {
                            // Display an error message if the query failed
                            echo 'Error: ' . mysqli_error($con);
                        }
                    } else if (isset($_SESSION['username'])) {
                        // The user is not an administrator.
                        // Get the user's rental history from the database
                        $getRentalsQuery = "SELECT * FROM rentals WHERE username = '{$_SESSION['username']} ' ORDER BY dropoff_datetime DESC";
                        $rentalsResult = mysqli_query($con, $getRentalsQuery);

                        // Check if the query was successful
                        if ($rentalsResult) {
                            // Fetch the results as an associative array
                            $rentals = mysqli_fetch_all($rentalsResult, MYSQLI_ASSOC);

                            // Join the rentals table with the cars table
                            $joinQuery = "SELECT rentals.*, cars.car_name, cars.rental_rate FROM rentals JOIN cars ON rentals.car_id = cars.id WHERE rentals.username = '{$_SESSION['username']}'";
                            $joinResult = mysqli_query($con, $joinQuery);

                            // Check if the join query was successful
                            if ($joinResult) {
                                // Fetch the results as an associative array
                                $rentals = mysqli_fetch_all($joinResult, MYSQLI_ASSOC);

                                echo '<div class="container mt-4">';
                                echo '<h3>Ваши аренды:</h3>';

                                // Separate rentals based on status
                                $expiredRentals = [];
                                $activeRentals = [];
                                $returnedRentals = [];

                                foreach ($rentals as $rental) {
                                    switch ($rental['status']) {
                                        case 'expired':
                                            $expiredRentals[] = $rental;
                                            break;
                                        case 'active':
                                            $activeRentals[] = $rental;
                                            break;
                                        case 'returned':
                                            $returnedRentals[] = $rental;
                                            break;
                                    }
                                }

                                // Merge the arrays in the desired order
                                $sortedRentals = array_merge($expiredRentals, $activeRentals, $returnedRentals);

                                foreach ($sortedRentals as $rental) {
                                    $total_rent_cost = $rental['rental_days'] * $rental['rental_rate'];
                                    $isExpired = ($rental['status'] === 'expired');

                                    $alertClass = $isExpired ? 'alert-danger' : 'alert-primary';
                                
                                    echo '<div class="card mt-3">';
                                    echo '<div class="card-body">';

                                    echo '<h5 class="card-title">' . $alertMessage . '</h5>';
    
                                    // Display an alert for expired rentals
                                    if ($isExpired) {
                                        echo '<div class="alert ' . $alertClass . '" role="alert">';
                                        echo 'Срок аренды истек. Пожалуйста, верните автомобиль.';
                                        echo '</div>';
                                    }
                                    
                                    echo '<h5 class="card-title">Подробности аренды</h5>';
                                    echo '<ul class="list-group list-group-flush">';
                                    echo "<li class='list-group-item'>Состояние: {$rental['status']}</li>";
                                    echo "<li class='list-group-item'>Место получения: {$rental['pickup_location']}</li>";
                                    echo "<li class='list-group-item'>Место возврата: {$rental['dropoff_location']}</li>";
                                    echo "<li class='list-group-item'>Дата и время получения:  {$rental['pickup_datetime']}</li>";
                                    echo "<li class='list-group-item'>Дата и время возврата: {$rental['dropoff_datetime']}</li>";
                                    echo "<li class='list-group-item'>Название авто: {$rental['car_name']}</li>";
                                    echo "<li class='list-group-item'>Дней аренды: {$rental['rental_days']}</li>";
                                    echo "<li class='list-group-item'>Общая стоимость аренды: \${$total_rent_cost}</li>";
                                    echo '</ul>';

                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                // Display an error message if the join query failed
                                echo 'Error: ' . mysqli_error($con);
                            }
                        } else {
                            // Display an error message if the query failed
                            echo 'Error: ' . mysqli_error($con);
                        }

                        // Close the database connection
                        mysqli_close($con);
                    }

                ?>

    </main>
    <div class="container">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            </ul>
            <p class="text-center text-body-secondary">© 2023 Joeyy car rent</p>
        </footer>
    </div>
    
    <div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="loginModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Войти</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-5 pt-0">
            <form action="login_process.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Username" required>
                <label for="floatingInput">Имя пользователя</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="password" name="password" placeholder="Password" required>
                <label for="floatingPassword">Пароль</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Войти</button>
            </form>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="signupModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Регистрация</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-5 pt-0">
            <form action="register_process.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Username" required>
                <label for="floatingInput">Имя пользователя</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="password" name="password" placeholder="Password" required>
                <label for="floatingPassword">Пароль</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="confirm_password" name="confirm_password" placeholder="Password" required>
                <label for="floatingPassword">Потвердите пароль</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Зарегистрироваться</button>
            </form>
        </div>
        </div>
    </div>
    </div>


    </body>
</html>
